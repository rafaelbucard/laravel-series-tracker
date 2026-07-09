<?php

namespace App\Repositories\Contracts;

use App\Models\Series;
use App\Models\User;
use Illuminate\Support\Collection;

interface SeriesRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function createForUser(User $user, array $attributes): Series;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Series $series, array $attributes): Series;

    public function delete(Series $series): void;

    public function listForUserWithCounts(User $user): Collection;

    public function listForUserWithSeasons(User $user): Collection;

    public function loadSeasonsWithEpisodeCounts(Series $series): Series;

    public function namesForUser(User $user): Collection;
}
