<?php

namespace App\Repositories\Contracts;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TopicRepositoryInterface
{
    /**
     * @param  array{series_id?:int|null, sort?:string|null}  $filters
     */
    public function paginateWithFilters(array $filters, int $perPage = 20): LengthAwarePaginator;

    /**
     * @param  array{title:string, body:string, series_id?:int|null}  $attributes
     */
    public function createForUser(User $user, array $attributes): Topic;

    public function loadForShow(Topic $topic): Topic;
}
