<?php

namespace App\Providers;

use App\Repositories\CreateSeriesRepository;
use App\Repositories\SeriesRepository;
use Illuminate\Support\ServiceProvider;

class SeriesRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SeriesRepository::class, CreateSeriesRepository::class);
    }
}
