<?php

use App\Models\Series;
use App\Models\StreamingService;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows series belonging to the authenticated user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    Series::factory()->for($user)->create(['name' => 'My Series']);
    Series::factory()->for($otherUser)->create(['name' => 'Not Mine']);

    actingAs($user)
        ->get(route('series.index'))
        ->assertOk()
        ->assertSee('My Series')
        ->assertDontSee('Not Mine');
});

it('creates a series with seasons and episodes', function () {
    $user = User::factory()->create();
    $service = StreamingService::factory()->create();

    actingAs($user)
        ->post(route('series.store'), [
            'name' => 'Loki',
            'streaming_service_id' => $service->id,
            'qt_seasons' => 2,
            'qt_episodes' => 6,
        ])
        ->assertRedirect(route('series.index'));

    $series = Series::where('name', 'Loki')->first();
    expect($series)->not->toBeNull()
        ->and($series->user_id)->toBe($user->id)
        ->and($series->streaming_service_id)->toBe($service->id)
        ->and($series->seasons()->count())->toBe(2)
        ->and($series->episodes()->count())->toBe(12);
});

it('does not allow editing another user series', function () {
    $owner = User::factory()->create();
    $intruder = User::factory()->create();
    $series = Series::factory()->for($owner)->create();

    actingAs($intruder)
        ->get(route('series.edit', $series))
        ->assertForbidden();

    actingAs($intruder)
        ->put(route('series.update', $series), ['name' => 'Hacked'])
        ->assertForbidden();

    expect($series->fresh()->name)->not->toBe('Hacked');
});

it('deletes a series', function () {
    $user = User::factory()->create();
    $series = Series::factory()->for($user)->create();

    actingAs($user)
        ->delete(route('series.destroy', $series))
        ->assertRedirect(route('series.index'));

    expect(Series::find($series->id))->toBeNull();
});
