<?php

namespace App\Handlers;

use App\Models\Series;

class CreateSeriesService
{
    public function execute($name, $qtSeason, $epBySeason) {

        $series= new Series;
        $series->name = $name;
        $series->saveOrFail();
        $qtSeasons = $qtSeason;
        for ($i = 1; $i <= $qtSeasons; $i++) {
             $season = $series->seasons()->create(['number' => $i]);

             for ($j = 1; $j <= $epBySeason; $j++) {
                $season->episodes()->create(['number' => $j]);
            }

        }

        return $series;
    }   
}