<?php

namespace App\Http\Controllers;

class SeriesController extends Controller
{

    public function listarSeries() {
        $series = [
            'Dexter',
            'Breaking Bad',
            'The Witcher'
        ];

        $html = "<ul>";
        foreach ($series as $serie){
            $html .="<li>$serie</li>";
        }
        $html .= "</ul>";

        return $html;
    }

}