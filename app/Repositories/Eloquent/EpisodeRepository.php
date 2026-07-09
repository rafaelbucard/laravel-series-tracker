<?php

namespace App\Repositories\Eloquent;

use App\Models\Episode;
use App\Models\Season;
use App\Models\User;
use App\Repositories\Contracts\EpisodeRepositoryInterface;
use Illuminate\Support\Collection;

class EpisodeRepository implements EpisodeRepositoryInterface
{
    public function bulkInsert(Season $season, int $count): void
    {
        if ($count <= 0) {
            return;
        }

        $maxNumber = (int) ($season->episodes()->max('number') ?? 0);

        $episodes = [];
        for ($number = $maxNumber + 1; $number <= $maxNumber + $count; $number++) {
            $episodes[] = [
                'season_id' => $season->id,
                'number' => $number,
                'watched' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $season->episodes()->insert($episodes);
    }

    public function deleteHighest(Season $season, int $count): void
    {
        if ($count <= 0) {
            return;
        }

        $idsToRemove = $season->episodes()
            ->orderByDesc('number')
            ->limit($count)
            ->pluck('id');

        $season->episodes()->whereIn('id', $idsToRemove)->delete();
    }

    public function markWatched(Season $season, array $ids): void
    {
        $season->episodes()
            ->whereIn('id', $ids)
            ->update(['watched' => true, 'updated_at' => now()]);
    }

    public function markUnwatched(Season $season, array $ids): void
    {
        $season->episodes()
            ->whereNotIn('id', $ids)
            ->update(['watched' => false, 'updated_at' => now()]);
    }

    public function recentWatchedForUser(User $user, int $limit): Collection
    {
        return Episode::query()
            ->where('watched', true)
            ->whereHas('season.series', fn ($q) => $q->where('user_id', $user->id))
            ->with(['season.series'])
            ->orderByDesc('updated_at')
            ->limit($limit)
            ->get();
    }
}
