<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Serie;
use App\Models\Temporada;

class TemporadasController extends Controller
{
    public function index(int $serieId) {
        $temporadas = Serie::find($serieId)->temporada;
        $serie = Serie::find($serieId);
        return view('temporadas.index', compact('temporadas','serie'));
    }
}
