<?php

namespace App\Repositories\Contracts;

use App\Models\Comment;
use App\Models\Topic;

interface CommentRepositoryInterface
{
    public function findInTopic(Topic $topic, int $id): ?Comment;

    /**
     * @param  array{user_id:int, parent_id?:int|null, body:string}  $attributes
     */
    public function createForTopic(Topic $topic, array $attributes): Comment;
}
