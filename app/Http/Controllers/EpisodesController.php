<?php

namespace App\Http\Controllers;

use App\Models\Season;
use App\Repositories\Contracts\SeasonRepositoryInterface;
use App\Services\EpisodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EpisodesController extends Controller
{
    public function __construct(
        private readonly EpisodeService $episodes,
        private readonly SeasonRepositoryInterface $seasons,
    ) {
        //
    }

    public function index(Season $season): View
    {
        $this->authorize('view', $season->series);

        $season = $this->seasons->loadEpisodesAndSeries($season);

        return view('episodes.index', [
            'season' => $season,
            'episodes' => $season->episodes,
        ]);
    }

    public function update(Request $request, Season $season): RedirectResponse
    {
        $this->authorize('update', $season->series);

        $watchedIds = (array) $request->input('episodes', []);

        $this->episodes->syncWatched($season, $watchedIds);

        return redirect()
            ->route('episodes.index', $season)
            ->with('status', 'Episódios atualizados com sucesso!');
    }
}
