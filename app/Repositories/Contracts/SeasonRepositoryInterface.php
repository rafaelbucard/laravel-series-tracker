<?php

namespace App\Repositories\Contracts;

use App\Models\Season;
use App\Models\Series;
use Illuminate\Support\Collection;

interface SeasonRepositoryInterface
{
    public function createForSeries(Series $series, int $number): Season;

    public function getForSeries(Series $series): Collection;

    public function getForSeriesWithEpisodes(Series $series): Collection;

    /**
     * @param  array<int>  $keptIds
     */
    public function deleteMissing(Series $series, array $keptIds): void;

    public function updateNumber(Season $season, int $number): void;

    public function countEpisodes(Season $season): int;

    public function loadEpisodesAndSeries(Season $season): Season;
}
