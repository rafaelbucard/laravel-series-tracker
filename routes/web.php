<?php

use App\Http\Controllers\SeriesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TemporadasController;
use App\Http\Controllers\EpisodiosController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/series');
});
Route::resource('/series', SeriesController::class)->except(['show']);
Route::get('/series/{serieId}/temporadas', [TemporadasController::class,'index']);
//Route::get('/series/update-serie/{series}', [SeriesController::class,'edit'])->name('series.update');
Route::get('/temporadas/{idTemporadas}/episodios', [EpisodiosController::class,'index']);
