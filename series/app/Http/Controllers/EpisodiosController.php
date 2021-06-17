<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Temporada;
use App\Models\Episodio;

class EpisodiosController extends Controller
{
    public function index($idTemporada) {
        $temporada = Temporada::find($idTemporada);
        $episodios = $temporada->episodios;

        return view('episodios.index',compact('episodios'));
    }
}
