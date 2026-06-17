<?php

use App\Models\Topic;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('lists topics on the community index', function () {
    $user = User::factory()->create();
    Topic::factory()->for($user)->create(['title' => 'Discussion topic']);

    actingAs($user)
        ->get(route('community.index'))
        ->assertOk()
        ->assertSee('Discussion topic');
});

it('creates a topic and generates a unique slug', function () {
    $user = User::factory()->create();
    Topic::factory()->for($user)->create(['title' => 'Hello World', 'slug' => 'hello-world']);

    actingAs($user)
        ->post(route('community.topics.store'), [
            'title' => 'Hello World',
            'body' => 'Lorem ipsum dolor sit amet, consectetur.',
        ])
        ->assertRedirect();

    expect(Topic::where('slug', 'hello-world-2')->exists())->toBeTrue();
});

it('validates topic input', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('community.topics.store'), [
            'title' => 'no',
            'body' => '',
        ])
        ->assertSessionHasErrors(['title', 'body']);
});

it('shows topic detail by slug', function () {
    $user = User::factory()->create();
    $topic = Topic::factory()->for($user)->create();

    actingAs($user)
        ->get(route('community.topics.show', $topic))
        ->assertOk()
        ->assertSee($topic->title);
});
