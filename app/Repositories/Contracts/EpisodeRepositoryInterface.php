<?php

namespace App\Repositories\Contracts;

use App\Models\Season;
use App\Models\User;
use Illuminate\Support\Collection;

interface EpisodeRepositoryInterface
{
    /**
     * Append $count episodes to the season, numbered right after the current highest.
     */
    public function bulkInsert(Season $season, int $count): void;

    /**
     * Remove the $count highest-numbered episodes of the season.
     */
    public function deleteHighest(Season $season, int $count): void;

    /**
     * @param  array<int>  $ids
     */
    public function markWatched(Season $season, array $ids): void;

    /**
     * @param  array<int>  $ids
     */
    public function markUnwatched(Season $season, array $ids): void;

    public function recentWatchedForUser(User $user, int $limit): Collection;
}
