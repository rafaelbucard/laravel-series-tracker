<?php

use App\Http\Controllers\EpisodesController;
use App\Http\Controllers\SeriesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeasonsController;
use App\Http\Middleware\Autenticator;
use Illuminate\Support\Facades\Auth;

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
})->middleware('auth');
Route::resource('/series', SeriesController::class)->except(['show'])->middleware('auth');
Route::get('/series/{series}/seasons', [SeasonsController::class,'index'])->name('seasons.index')->middleware('auth');
Route::get('/seasons/{season}/episodes', [EpisodesController::class,'index'])->name('episodes.index')->middleware('auth');
Route::post('/seasons/{season}/episodes', [EpisodesController::class,'update'])->name('episodes.update')->middleware('auth');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout')->middleware('auth');
Auth::routes();


