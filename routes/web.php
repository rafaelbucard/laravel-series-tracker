<?php

use App\Http\Controllers\EpisodesController;
use App\Http\Controllers\SeriesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeasonsController;

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
Route::get('/series/{series}/seasons', [SeasonsController::class,'index'])->name('seasons.index');
Route::get('/seasons/{season}/episodes', [EpisodesController::class,'index'])->name('episodes.index');
Route::post('/seasons/{season}/episodes', [EpisodesController::class,'update'])->name('episodes.update');

