<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeriesFormRequest;
use App\Models\Serie;
use Illuminate\Http\Request;


class SeriesController extends Controller
{

    public function index(Request $request) 
    {
        $series = Serie::query()->orderBy('nome')->get();
        $mensagem = $request->session()->get('mensagem');
            
        return view('series.index', compact('series', 'mensagem'));
    }

    public function create() 
    {
        return view('series.create');
    }

    public function store(SeriesFormRequest $request)  
    {
       

         $serie = Serie::create($request->all());
         $request->session()->flash(
            'mensagem',
            "Serie {$serie->id} criada com sucesso {$serie->nome}"

    );
        return redirect()->route( route: 'listar_series');
    }

    public function destroy(Request $request ,$id) 
    {
        Serie::destroy($id);
        $request->session()->flash(
            'mensagem',
            "Serie {$id} Deletada"
        );
        return redirect()->route( route: 'listar_series');
    }

}
