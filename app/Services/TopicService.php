<?php

namespace App\Services;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TopicService
{
    /**
     * @param  array{series_id?:int|null, sort?:string|null}  $filters
     */
    public function list(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = Topic::query()
            ->with(['user', 'series'])
            ->withCount('comments');

        if (! empty($filters['series_id'])) {
            $query->where('series_id', $filters['series_id']);
        }

        $sort = $filters['sort'] ?? 'recent';

        match ($sort) {
            'commented' => $query->orderByDesc('comments_count')->orderByDesc('created_at'),
            default => $query->orderByDesc('created_at'),
        };

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * @param  array{title:string, body:string, series_id?:int|null}  $data
     */
    public function create(User $user, array $data): Topic
    {
        return $user->topics()->create([
            'title' => $data['title'],
            'body' => $data['body'],
            'series_id' => $data['series_id'] ?? null,
        ]);
    }
}
