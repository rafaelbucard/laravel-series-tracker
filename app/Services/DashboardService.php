<?php

namespace App\Services;

use App\Models\Episode;
use App\Models\Series;
use App\Models\User;
use Illuminate\Support\Collection;

class DashboardService
{
    /**
     * @return array{
     *     totals: array{series:int, episodes_total:int, episodes_watched:int, completed_series:int},
     *     in_progress: \Illuminate\Support\Collection,
     *     completed: \Illuminate\Support\Collection,
     *     continue_watching: \Illuminate\Support\Collection,
     *     recent_activity: \Illuminate\Support\Collection,
     * }
     */
    public function statsFor(User $user): array
    {
        $seriesQuery = $user->series()
            ->with(['streamingService', 'seasons.episodes'])
            ->orderBy('name');

        $allSeries = $seriesQuery->get();

        $totalEpisodes = 0;
        $watchedEpisodes = 0;

        $inProgress = collect();
        $completed = collect();

        foreach ($allSeries as $series) {
            $epCount = 0;
            $epWatched = 0;
            foreach ($series->seasons as $season) {
                foreach ($season->episodes as $ep) {
                    $epCount++;
                    if ($ep->watched) {
                        $epWatched++;
                    }
                }
            }
            $totalEpisodes += $epCount;
            $watchedEpisodes += $epWatched;

            $percent = $epCount > 0 ? (int) round(($epWatched / $epCount) * 100) : 0;
            $series->setAttribute('episodes_total', $epCount);
            $series->setAttribute('episodes_watched', $epWatched);
            $series->setAttribute('percent', $percent);

            if ($epCount > 0 && $epWatched === $epCount) {
                $completed->push($series);
            } elseif ($epWatched > 0) {
                $inProgress->push($series);
            }
        }

        return [
            'totals' => [
                'series' => $allSeries->count(),
                'episodes_total' => $totalEpisodes,
                'episodes_watched' => $watchedEpisodes,
                'completed_series' => $completed->count(),
            ],
            'in_progress' => $inProgress->sortByDesc('percent')->values(),
            'completed' => $completed->values(),
            'continue_watching' => $this->continueWatching($inProgress),
            'recent_activity' => $this->recentActivity($user),
        ];
    }

    private function continueWatching(Collection $inProgress): Collection
    {
        return $inProgress->map(function (Series $series) {
            $nextEpisode = null;

            foreach ($series->seasons->sortBy('number') as $season) {
                foreach ($season->episodes->sortBy('number') as $ep) {
                    if (! $ep->watched) {
                        $nextEpisode = [
                            'season_number' => $season->number,
                            'episode_number' => $ep->number,
                            'season_id' => $season->id,
                            'episode_id' => $ep->id,
                        ];
                        break 2;
                    }
                }
            }

            return [
                'series' => $series,
                'next' => $nextEpisode,
            ];
        })->filter(fn ($item) => $item['next'] !== null)->values();
    }

    private function recentActivity(User $user): Collection
    {
        return Episode::query()
            ->where('watched', true)
            ->whereHas('season.series', fn ($q) => $q->where('user_id', $user->id))
            ->with(['season.series'])
            ->orderByDesc('updated_at')
            ->limit(8)
            ->get();
    }
}
