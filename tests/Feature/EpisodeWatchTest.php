<?php

use App\Models\Episode;
use App\Models\Season;
use App\Models\Series;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('marks selected episodes as watched and the rest as not watched', function () {
    $user = User::factory()->create();
    $series = Series::factory()->for($user)->create();
    $season = Season::factory()->for($series)->create(['number' => 1]);

    $ep1 = Episode::factory()->for($season)->create(['number' => 1, 'watched' => false]);
    $ep2 = Episode::factory()->for($season)->create(['number' => 2, 'watched' => true]);
    $ep3 = Episode::factory()->for($season)->create(['number' => 3, 'watched' => false]);

    actingAs($user)
        ->post(route('episodes.update', $season), [
            'episodes' => [$ep1->id, $ep3->id],
        ])
        ->assertRedirect(route('episodes.index', $season));

    expect($ep1->fresh()->watched)->toBeTrue()
        ->and($ep2->fresh()->watched)->toBeFalse()
        ->and($ep3->fresh()->watched)->toBeTrue();
});

it('clears all watched flags when nothing is sent', function () {
    $user = User::factory()->create();
    $series = Series::factory()->for($user)->create();
    $season = Season::factory()->for($series)->create();

    Episode::factory()->for($season)->count(3)->create(['watched' => true]);

    actingAs($user)
        ->post(route('episodes.update', $season), [])
        ->assertRedirect(route('episodes.index', $season));

    expect($season->episodes()->where('watched', true)->count())->toBe(0);
});
