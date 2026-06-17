<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Topic;
use App\Models\User;

class CommentService
{
    /**
     * @param  array{body:string, parent_id?:int|null}  $data
     */
    public function create(User $user, Topic $topic, array $data): Comment
    {
        $parentId = $data['parent_id'] ?? null;

        // MVP keeps replies at a single level: replies to replies attach to root.
        if ($parentId) {
            $parent = Comment::query()->where('topic_id', $topic->id)->find($parentId);

            if (! $parent) {
                $parentId = null;
            } elseif ($parent->parent_id !== null) {
                $parentId = $parent->parent_id;
            }
        }

        return $topic->comments()->create([
            'user_id' => $user->id,
            'parent_id' => $parentId,
            'body' => $data['body'],
        ]);
    }
}
