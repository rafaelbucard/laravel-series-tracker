<?php

namespace App\Http\Controllers;

use App\Http\Requests\SyncSeasonsRequest;
use App\Models\Series;
use App\Repositories\Contracts\SeasonRepositoryInterface;
use App\Services\SeriesService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SeasonsController extends Controller
{
    public function __construct(
        private readonly SeriesService $seriesService,
        private readonly SeasonRepositoryInterface $seasons,
    ) {
        //
    }

    public function index(Series $series): View
    {
        $this->authorize('view', $series);

        return view('seasons.index', [
            'series' => $series,
            'seasons' => $this->seasons->getForSeriesWithEpisodes($series),
        ]);
    }

    public function sync(SyncSeasonsRequest $request, Series $series): RedirectResponse
    {
        $this->authorize('update', $series);

        $this->seriesService->syncSeasons($series, $request->validated()['seasons']);

        return redirect()
            ->route('series.edit', $series)
            ->with('status', 'Temporadas atualizadas com sucesso!');
    }
}
