<?php

namespace App\Providers;

use App\Models\Series;
use App\Policies\SeriesPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Series::class, SeriesPolicy::class);

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Vite::useBuildDirectory('build');
    }
}
