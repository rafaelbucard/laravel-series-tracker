<?php

namespace App\Http\Controllers;

class SeriesController extends Controller
{

    public function index() {
        $series = [
            'Dexter',
            'Breaking Bad',
            'The Witcher'
        ];

        

        return view('series.index', ['series' =>$series]);
    }

}
