<?php

namespace App\Services;

use App\Models\Season;
use App\Models\Series;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class SeriesService
{
    public function __construct(
        private readonly ImageService $imageService,
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

            $series = $user->series()->create([
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
                $season = $series->seasons()->create(['number' => $s]);

                $episodes = [];
                for ($e = 1; $e <= $qtEpisodes; $e++) {
                    $episodes[] = [
                        'season_id' => $season->id,
                        'number' => $e,
                        'watched' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                $season->episodes()->insert($episodes);
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

            $series->fill($attributes)->save();

            return $series->fresh();
        });
    }

    public function delete(Series $series): void
    {
        DB::transaction(function () use ($series) {
            $this->imageService->delete($series->cover_path);
            $series->delete();
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
            $existing = $series->seasons()->get()->keyBy('id');

            $keptIds = collect($seasons)
                ->pluck('id')
                ->filter()
                ->map(fn ($id) => (int) $id)
                ->all();

            $series->seasons()
                ->whereNotIn('id', $keptIds)
                ->get()
                ->each(fn (Season $season) => $season->delete());

            $number = 0;
            foreach ($seasons as $row) {
                $number++;
                $episodes = max(1, (int) ($row['episodes'] ?? 1));
                $id = ! empty($row['id']) ? (int) $row['id'] : null;

                $season = $id ? $existing->get($id) : null;

                if ($season === null) {
                    $season = $series->seasons()->create(['number' => $number]);
                    $this->adjustEpisodeCount($season, 0, $episodes);

                    continue;
                }

                if ($season->number !== $number) {
                    $season->update(['number' => $number]);
                }

                $current = $season->episodes()->count();
                $this->adjustEpisodeCount($season, $current, $episodes);
            }
        });
    }

    private function adjustEpisodeCount(Season $season, int $current, int $target): void
    {
        if ($target > $current) {
            $maxNumber = (int) ($season->episodes()->max('number') ?? 0);

            $episodes = [];
            for ($e = $maxNumber + 1; $e <= $maxNumber + ($target - $current); $e++) {
                $episodes[] = [
                    'season_id' => $season->id,
                    'number' => $e,
                    'watched' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $season->episodes()->insert($episodes);

            return;
        }

        if ($target < $current) {
            $idsToRemove = $season->episodes()
                ->orderByDesc('number')
                ->limit($current - $target)
                ->pluck('id');

            $season->episodes()->whereIn('id', $idsToRemove)->delete();
        }
    }
}
