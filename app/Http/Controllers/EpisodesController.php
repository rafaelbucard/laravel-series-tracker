<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use Illuminate\Http\Request;
use App\Models\Season;
use Illuminate\Support\Facades\DB;

class EpisodesController extends Controller
{
    public function index(Season $season) 
    {
        return view('episodes.index',[
            'episodes' => $season->episodes,
            'menssage' => null
        ]);
       
    }

    public function update(Request $request, Season $season) 
    {
        if(!is_null($request->episodes)){
            DB::beginTransaction();
            $watched = $request->episodes;
            $season->episodes->each(function (Episode $episode) use ($watched) {
            $episode->watched = in_array($episode->id, $watched);
            });
            $season->push();
            DB::commit();
        } else {
            DB::beginTransaction();
            $watched = $request->episodes;
            $season->episodes->each(function (Episode $episode) use ($watched) {
            $episode->watched = false;
            });
            $season->push();
            DB::commit();
        }

        return view('episodes.index',['episodes' => $season->episodes])->with('menssage' , 'Episódios marcados com sucesso!');

    }
}
