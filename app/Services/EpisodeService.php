<?php

namespace App\Services;

use App\Models\Season;
use App\Repositories\Contracts\EpisodeRepositoryInterface;
use Illuminate\Support\Facades\DB;

class EpisodeService
{
    public function __construct(
        private readonly EpisodeRepositoryInterface $episodes,
    ) {
        //
    }

    /**
     * Bulk-update watched flag for all episodes of a season.
     * Episodes whose ids are in $watchedIds become watched=true, the rest false.
     *
     * @param  array<int>  $watchedIds
     */
    public function syncWatched(Season $season, array $watchedIds): void
    {
        $watchedIds = array_map('intval', $watchedIds);

        DB::transaction(function () use ($season, $watchedIds) {
            $this->episodes->markWatched($season, $watchedIds);
            $this->episodes->markUnwatched($season, $watchedIds);
        });
    }
}
