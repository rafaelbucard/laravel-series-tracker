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

    public function edit(Serie $series) 
    {
        return view('series.update')->with('serie',$series);
    }


    public function store(SeriesFormRequest $request,CriadorDeSerie $criadorDeSerie)  
    {
        $serie = $criadorDeSerie->criarSerie(
            $request->nome, 
            $request->qtd_temporadas, 
            $request->qtd_episodios
        );

        return redirect()->route('series.index')->with( 'mensagem.sucesso',
        "Série, temporadas e episódios foram criados com sucesso : {$serie->nome}");
    }

    public function update(Serie $series, SeriesFormRequest $request)
    {
        $novoNome = $request->nome;

        $series->nome = $novoNome;
        $series->saveOrFail();
        return redirect()->route('series.index')->with( 'mensagem.sucesso',
        "Série atualizada com sucesso : {$novoNome}");
    }
    
    public function destroy(Serie $series) 
    {
        $series->delete();
        return redirect()->route( 'series.index')->with( 'mensagem.sucesso',
        "O seriado, {$series->nome} removido com sucesso!");
    }

}
