<?php

use App\Http\Controllers\Community\CommentController;
use App\Http\Controllers\Community\TopicController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EpisodesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicProfileController;
use App\Http\Controllers\SeasonsController;
use App\Http\Controllers\SeriesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('home');

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Series
    Route::get('/series/omdb/search', [SeriesController::class, 'omdbSearch'])->name('series.omdb.search');
    Route::resource('series', SeriesController::class)->except(['show']);

    // Seasons & Episodes
    Route::get('/series/{series}/seasons', [SeasonsController::class, 'index'])->name('seasons.index');
    Route::get('/seasons/{season}/episodes', [EpisodesController::class, 'index'])->name('episodes.index');
    Route::post('/seasons/{season}/episodes', [EpisodesController::class, 'update'])->name('episodes.update');

    // Community
    Route::prefix('community')->name('community.')->group(function () {
        Route::get('/', [TopicController::class, 'index'])->name('index');
        Route::resource('topics', TopicController::class)->except(['edit', 'update', 'destroy']);
        Route::post('topics/{topic}/comments', [CommentController::class, 'store'])->name('comments.store');
    });

    // Public profiles
    Route::get('/users/{user}', [PublicProfileController::class, 'show'])->name('users.show');

    // Profile (próprio)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
