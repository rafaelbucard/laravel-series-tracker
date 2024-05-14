<?php

namespace App\Handlers;

use App\Models\Serie;

class CriadorDeSerie
{
    public function criarSerie($nomeSerie, $qtdTemporada, $epPorTemporada) {

        $serie = new Serie;
        $serie->nome = $nomeSerie;
        $serie->saveOrFail();
        $qtdTemporadas = $qtdTemporada;
        for ($i = 1; $i <= $qtdTemporadas; $i++) {
             $temporada = $serie->temporadas()->create(['numero' => $i]);

             for ($j = 1; $j <= $epPorTemporada; $j++) {
                $temporada->episodios()->create(['numero' => $j]);
            }

        }

        return $serie;
    }   
}