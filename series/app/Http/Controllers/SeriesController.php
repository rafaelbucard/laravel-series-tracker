<?php

namespace App\Http\Controllers;

use App\Models\Serie;
use Illuminate\Http\Request;


class SeriesController extends Controller
{

    public function index() 
    {
        $series = [
            'Dexter',
            'Breaking Bad',
            'The Witcher'
        ];

        

        return view('series.index', ['series' =>$series]);
    }

    public function create() 
    {
        return view('series.create');

    }

    public function store(Request $request)  
    {
        $nome =  $request->nome;

        $serie = new Serie();

        $serie->nome = $nome;

        $serie->save();

        return view('series.create');

    }

}
