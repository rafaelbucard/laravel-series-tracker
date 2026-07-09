<?php

namespace App\Repositories\Eloquent;

use App\Models\Season;
use App\Models\Series;
use App\Repositories\Contracts\SeasonRepositoryInterface;
use Illuminate\Support\Collection;

class SeasonRepository implements SeasonRepositoryInterface
{
    public function createForSeries(Series $series, int $number): Season
    {
        return $series->seasons()->create(['number' => $number]);
    }

    public function getForSeries(Series $series): Collection
    {
        return $series->seasons()->get();
    }

    public function getForSeriesWithEpisodes(Series $series): Collection
    {
        return $series->seasons()
            ->with('episodes')
            ->orderBy('number')
            ->get();
    }

    public function deleteMissing(Series $series, array $keptIds): void
    {
        $series->seasons()
            ->whereNotIn('id', $keptIds)
            ->get()
            ->each(fn (Season $season) => $season->delete());
    }

    public function updateNumber(Season $season, int $number): void
    {
        $season->update(['number' => $number]);
    }

    public function countEpisodes(Season $season): int
    {
        return $season->episodes()->count();
    }

    public function loadEpisodesAndSeries(Season $season): Season
    {
        return $season->load('episodes', 'series');
    }
}
