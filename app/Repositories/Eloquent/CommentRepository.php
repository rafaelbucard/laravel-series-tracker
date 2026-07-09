<?php

namespace App\Repositories\Eloquent;

use App\Models\Comment;
use App\Models\Topic;
use App\Repositories\Contracts\CommentRepositoryInterface;

class CommentRepository implements CommentRepositoryInterface
{
    public function findInTopic(Topic $topic, int $id): ?Comment
    {
        return Comment::query()->where('topic_id', $topic->id)->find($id);
    }

    public function createForTopic(Topic $topic, array $attributes): Comment
    {
        return $topic->comments()->create([
            'user_id' => $attributes['user_id'],
            'parent_id' => $attributes['parent_id'] ?? null,
            'body' => $attributes['body'],
        ]);
    }
}
