<?php

use App\Models\Series;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('shows public profile with user series', function () {
    $user = User::factory()->create(['profile_is_public' => true]);
    Series::factory()->for($user)->create(['name' => 'Public Series']);

    $viewer = User::factory()->create();

    actingAs($viewer)
        ->get(route('users.show', $user))
        ->assertOk()
        ->assertSee($user->name)
        ->assertSee('Public Series');
});

it('returns 404 for private profile when viewed by others', function () {
    $user = User::factory()->create(['profile_is_public' => false]);
    $viewer = User::factory()->create();

    actingAs($viewer)
        ->get(route('users.show', $user))
        ->assertNotFound();
});

it('lets the user view their own private profile', function () {
    $user = User::factory()->create(['profile_is_public' => false]);

    actingAs($user)
        ->get(route('users.show', $user))
        ->assertOk()
        ->assertSee($user->name);
});
