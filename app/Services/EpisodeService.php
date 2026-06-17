<?php

namespace App\Services;

use App\Models\Season;
use Illuminate\Support\Facades\DB;

class EpisodeService
{
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
            $season->episodes()
                ->whereIn('id', $watchedIds)
                ->update(['watched' => true, 'updated_at' => now()]);

            $season->episodes()
                ->whereNotIn('id', $watchedIds)
                ->update(['watched' => false, 'updated_at' => now()]);
        });
    }
}
