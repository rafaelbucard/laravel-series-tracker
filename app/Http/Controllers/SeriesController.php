<?php

namespace App\Http\Controllers;

use App\Handlers\CriadorDeSerie;
use App\Http\Requests\SeriesFormRequest;
use App\Models\Serie;
use Illuminate\Http\Request;


class SeriesController extends Controller
{

    public function index(Request $request) 
    {
        $series = Serie::query()->orderBy('nome')->get();
        $mensagem = $request->session()->get('mensagem.sucesso');
        return view('series.index', compact('series', 'mensagem'));
    }

    public function create() 
    {
        return view('series.create');
    }

    public function store(SeriesFormRequest $request,CriadorDeSerie $criadorDeSerie)  
    {
        $serie = $criadorDeSerie->criarSerie(
            $request->nome, 
            $request->qtd_temporadas, 
            $request->qtd_episodios
        );

        $request->session()->flash(
            'mensagem.sucesso',
            "Série, temporadas e episódios foram criados com sucesso : {$serie->nome}"

    );
        return redirect()->route('series.index');
    }

    public function update($id, Request $request)
    {
        $novoNome = $request->nome;

        $serie = Serie::find($id);
        $serie->nome = $novoNome;
        $serie->save();
    }
    
    public function destroy(Request $request , $id) 
    {
        $serie = Serie::where('id',$id)->first();
        Serie::destroy($id);
        $request->session()->flash(
            'mensagem.sucesso',
            "O seriado, {$serie->nome} removido com sucesso!"
        );
        return redirect()->route( 'series.index');
    }

}
