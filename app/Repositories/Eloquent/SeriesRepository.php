<?php

namespace App\Repositories\Eloquent;

use App\Models\Series;
use App\Models\User;
use App\Repositories\Contracts\SeriesRepositoryInterface;
use Illuminate\Support\Collection;

class SeriesRepository implements SeriesRepositoryInterface
{
    public function createForUser(User $user, array $attributes): Series
    {
        return $user->series()->create($attributes);
    }

    public function update(Series $series, array $attributes): Series
    {
        $series->fill($attributes)->save();

        return $series->fresh();
    }

    public function delete(Series $series): void
    {
        $series->delete();
    }

    public function listForUserWithCounts(User $user): Collection
    {
        return $user->series()
            ->with('streamingService')
            ->withCount(['seasons', 'episodes as episodes_total', 'episodes as episodes_watched' => function ($q) {
                $q->where('watched', true);
            }])
            ->orderBy('name')
            ->get();
    }

    public function listForUserWithSeasons(User $user): Collection
    {
        return $user->series()
            ->with(['streamingService', 'seasons.episodes'])
            ->orderBy('name')
            ->get();
    }

    public function loadSeasonsWithEpisodeCounts(Series $series): Series
    {
        return $series->load(['seasons' => function ($q) {
            $q->withCount('episodes')->orderBy('number');
        }]);
    }

    public function namesForUser(User $user): Collection
    {
        return $user->series()->orderBy('name')->get(['id', 'name']);
    }
}
