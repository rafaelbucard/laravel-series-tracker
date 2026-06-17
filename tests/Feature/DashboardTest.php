<?php

use App\Models\Episode;
use App\Models\Season;
use App\Models\Series;
use App\Models\User;
use App\Services\DashboardService;

use function Pest\Laravel\actingAs;

it('redirects unauthenticated visitors from dashboard', function () {
    $this->get(route('dashboard'))->assertRedirect(route('login'));
});

it('renders dashboard for authenticated user', function () {
    $user = User::factory()->create();

    actingAs($user)->get(route('dashboard'))->assertOk()->assertSee('Dashboard');
});

it('computes statistics correctly', function () {
    $user = User::factory()->create();
    $series = Series::factory()->for($user)->create();
    $season = Season::factory()->for($series)->create();

    Episode::factory()->for($season)->create(['watched' => true]);
    Episode::factory()->for($season)->create(['watched' => true]);
    Episode::factory()->for($season)->create(['watched' => false]);

    $stats = app(DashboardService::class)->statsFor($user->fresh());

    expect($stats['totals']['series'])->toBe(1)
        ->and($stats['totals']['episodes_total'])->toBe(3)
        ->and($stats['totals']['episodes_watched'])->toBe(2)
        ->and($stats['totals']['completed_series'])->toBe(0)
        ->and($stats['in_progress'])->toHaveCount(1);
});
