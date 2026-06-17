<?php

namespace App\Http\Controllers;

use App\Models\Series;
use Illuminate\View\View;

class SeasonsController extends Controller
{
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
}
