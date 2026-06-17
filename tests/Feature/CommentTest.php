<?php

use App\Models\Comment;
use App\Models\Topic;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('adds a comment to a topic', function () {
    $user = User::factory()->create();
    $topic = Topic::factory()->for($user)->create();

    actingAs($user)
        ->post(route('community.comments.store', $topic), [
            'body' => 'Great topic, I agree!',
        ])
        ->assertRedirect(route('community.topics.show', $topic));

    expect($topic->comments()->count())->toBe(1)
        ->and($topic->comments()->first()->body)->toBe('Great topic, I agree!');
});

it('attaches reply to root parent (1-level threading)', function () {
    $user = User::factory()->create();
    $topic = Topic::factory()->for($user)->create();
    $root = Comment::factory()->for($topic)->for($user)->create();
    $reply = Comment::factory()->for($topic)->for($user)->create(['parent_id' => $root->id]);

    actingAs($user)
        ->post(route('community.comments.store', $topic), [
            'body' => 'Reply to a reply',
            'parent_id' => $reply->id,
        ])
        ->assertRedirect();

    expect(Comment::where('body', 'Reply to a reply')->first()->parent_id)->toBe($root->id);
});

it('validates comment body', function () {
    $user = User::factory()->create();
    $topic = Topic::factory()->for($user)->create();

    actingAs($user)
        ->post(route('community.comments.store', $topic), ['body' => ''])
        ->assertSessionHasErrors('body');
});
