<?php

namespace App\Services;

use App\Models\Topic;
use App\Models\User;
use App\Repositories\Contracts\TopicRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TopicService
{
    public function __construct(
        private readonly TopicRepositoryInterface $topics,
    ) {
        //
    }

    /**
     * @param  array{series_id?:int|null, sort?:string|null}  $filters
     */
    public function list(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return $this->topics->paginateWithFilters($filters, $perPage);
    }

    /**
     * @param  array{title:string, body:string, series_id?:int|null}  $data
     */
    public function create(User $user, array $data): Topic
    {
        return $this->topics->createForUser($user, $data);
    }
}
