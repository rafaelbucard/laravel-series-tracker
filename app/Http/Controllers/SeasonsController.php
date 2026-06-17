<?php

namespace App\Http\Controllers;

use App\Http\Requests\SyncSeasonsRequest;
use App\Models\Series;
use App\Services\SeriesService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SeasonsController extends Controller
{
    public function __construct(
        private readonly SeriesService $seriesService,
    ) {
        //
    }

    public function index(Series $series): View
    {
        $this->authorize('view', $series);

        $seasons = $series->seasons()
            ->with('episodes')
            ->orderBy('number')
            ->get();

        return view('seasons.index', [
            'series' => $series,
            'seasons' => $seasons,
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
