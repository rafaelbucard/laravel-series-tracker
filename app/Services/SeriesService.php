<?php

namespace App\Services;

use App\Models\Season;
use App\Models\Series;
use App\Models\User;
use App\Repositories\Contracts\EpisodeRepositoryInterface;
use App\Repositories\Contracts\SeasonRepositoryInterface;
use App\Repositories\Contracts\SeriesRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class SeriesService
{
    public function __construct(
        private readonly ImageService $imageService,
        private readonly SeriesRepositoryInterface $series,
        private readonly SeasonRepositoryInterface $seasons,
        private readonly EpisodeRepositoryInterface $episodes,
    ) {
        //
    }

    /**
     * @param  array{
     *     name: string,
     *     streaming_service_id?: int|null,
     *     synopsis?: string|null,
     *     year?: int|null,
     *     imdb_id?: string|null,
     *     qt_seasons: int,
     *     qt_episodes: int,
     *     cover_file?: \Illuminate\Http\UploadedFile|null,
     *     cover_url?: string|null,
     * } $data
     */
    public function create(User $user, array $data): Series
    {
        return DB::transaction(function () use ($user, $data) {
            $coverPath = null;
            $coverUrl = null;

            if (! empty($data['cover_file']) && $data['cover_file'] instanceof UploadedFile) {
                $coverPath = $this->imageService->storeUploaded($data['cover_file']);
            } elseif (! empty($data['cover_url'])) {
                $stored = $this->imageService->storeFromUrl($data['cover_url']);
                if ($stored) {
                    $coverPath = $stored;
                } else {
                    $coverUrl = $data['cover_url'];
                }
            }

            $series = $this->series->createForUser($user, [
                'streaming_service_id' => $data['streaming_service_id'] ?? null,
                'name' => $data['name'],
                'synopsis' => $data['synopsis'] ?? null,
                'year' => $data['year'] ?? null,
                'imdb_id' => $data['imdb_id'] ?? null,
                'cover_path' => $coverPath,
                'cover_url' => $coverUrl,
            ]);

            $qtSeasons = max(1, (int) ($data['qt_seasons'] ?? 1));
            $qtEpisodes = max(1, (int) ($data['qt_episodes'] ?? 1));

            for ($s = 1; $s <= $qtSeasons; $s++) {
                $season = $this->seasons->createForSeries($series, $s);
                $this->episodes->bulkInsert($season, $qtEpisodes);
            }

            return $series;
        });
    }

    /**
     * @param  array{
     *     name?: string,
     *     streaming_service_id?: int|null,
     *     synopsis?: string|null,
     *     year?: int|null,
     *     imdb_id?: string|null,
     *     cover_file?: \Illuminate\Http\UploadedFile|null,
     *     cover_url?: string|null,
     * } $data
     */
    public function update(Series $series, array $data): Series
    {
        return DB::transaction(function () use ($series, $data) {
            $attributes = array_filter([
                'name' => $data['name'] ?? null,
                'streaming_service_id' => array_key_exists('streaming_service_id', $data) ? $data['streaming_service_id'] : null,
                'synopsis' => $data['synopsis'] ?? null,
                'year' => $data['year'] ?? null,
                'imdb_id' => $data['imdb_id'] ?? null,
            ], fn ($value) => $value !== null);

            if (! empty($data['cover_file']) && $data['cover_file'] instanceof UploadedFile) {
                $this->imageService->delete($series->cover_path);
                $attributes['cover_path'] = $this->imageService->storeUploaded($data['cover_file']);
                $attributes['cover_url'] = null;
            } elseif (array_key_exists('cover_url', $data) && $data['cover_url']) {
                $this->imageService->delete($series->cover_path);
                $stored = $this->imageService->storeFromUrl($data['cover_url']);

                if ($stored) {
                    $attributes['cover_path'] = $stored;
                    $attributes['cover_url'] = null;
                } else {
                    $attributes['cover_path'] = null;
                    $attributes['cover_url'] = $data['cover_url'];
                }
            }

            return $this->series->update($series, $attributes);
        });
    }

    public function delete(Series $series): void
    {
        DB::transaction(function () use ($series) {
            $this->imageService->delete($series->cover_path);
            $this->series->delete($series);
        });
    }

    /**
     * Reconcile the seasons (and their episode counts) of a series.
     *
     * Existing seasons are matched by id; missing ones are deleted, new ones
     * (without id) are created. Seasons are renumbered sequentially (1..N).
     * Episode counts grow by appending new episodes and shrink by removing the
     * highest-numbered ones (even when watched).
     *
     * @param  array<int, array{id?: int|null, episodes: int}>  $seasons
     */
    public function syncSeasons(Series $series, array $seasons): void
    {
        DB::transaction(function () use ($series, $seasons) {
            $existing = $this->seasons->getForSeries($series)->keyBy('id');

            $keptIds = collect($seasons)
                ->pluck('id')
                ->filter()
                ->map(fn ($id) => (int) $id)
                ->all();

            $this->seasons->deleteMissing($series, $keptIds);

            $number = 0;
            foreach ($seasons as $row) {
                $number++;
                $episodes = max(1, (int) ($row['episodes'] ?? 1));
                $id = ! empty($row['id']) ? (int) $row['id'] : null;

                $season = $id ? $existing->get($id) : null;

                if ($season === null) {
                    $season = $this->seasons->createForSeries($series, $number);
                    $this->episodes->bulkInsert($season, $episodes);

                    continue;
                }

                if ($season->number !== $number) {
                    $this->seasons->updateNumber($season, $number);
                }

                $current = $this->seasons->countEpisodes($season);
                $this->adjustEpisodeCount($season, $current, $episodes);
            }
        });
    }

    private function adjustEpisodeCount(Season $season, int $current, int $target): void
    {
        if ($target > $current) {
            $this->episodes->bulkInsert($season, $target - $current);

            return;
        }

        if ($target < $current) {
            $this->episodes->deleteHighest($season, $current - $target);
        }
    }
}
