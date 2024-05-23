<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Season;

class EpisodiosController extends Controller
{
    public function index($idTemporada) {
        $temporada = Season::find($idTemporada);
        $episodios = $temporada->episodios;

        return view('episodios.index',compact('episodios'));
    }
}
