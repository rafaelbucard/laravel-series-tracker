<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Season;

class EpisodesController extends Controller
{
    public function index(Season $season) 
    {
        return view('episodes.index',['episodes' => $season->episodes]);
    }
}
