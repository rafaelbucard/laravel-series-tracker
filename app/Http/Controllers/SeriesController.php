<?php

namespace App\Http\Controllers;

use App\Handlers\CreateSeriesService;
use App\Http\Requests\SeriesFormRequest;
use App\Models\Series;
use Illuminate\Http\Request;


class SeriesController extends Controller
{

    public function index(Request $request) 
    {
        $series = Series::query()->orderBy('name')->get();
        $menssage = $request->session()->get('menssage.success');
        return view('series.index', compact('series', 'menssage'));
    }

    public function create() 
    {
        return view('series.create');
    }

    public function edit(Series $series) 
    {
        return view('series.update')->with('series',$series);
    }


    public function store(SeriesFormRequest $request,CreateSeriesService $CreateSeriesService)  
    {
        $series= $CreateSeriesService->execute(
            $request->name, 
            $request->qt_seasons, 
            $request->qt_episodes
        );

        return redirect()->route('series.index')->with( 'menssage.success',
        "Série, temporadas e episódios foram criados com sucesso : {$series->nome}");
    }

    public function update(Series $series, SeriesFormRequest $request)
    {
        $newName = $request->name;

        $series->name = $newName;
        $series->saveOrFail();
        return redirect()->route('series.index')->with( 'menssage.success',
        "Série atualizada com sucesso : {$newName}");
    }
    
    public function destroy(Series$series) 
    {
        $series->delete();
        return redirect()->route( 'series.index')->with( 'menssage.success',
        "O seriado, {$series->nome} removido com sucesso!");
    }

}
