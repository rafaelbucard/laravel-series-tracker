<?php

namespace App\Repositories\Eloquent;

use App\Models\Topic;
use App\Models\User;
use App\Repositories\Contracts\TopicRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TopicRepository implements TopicRepositoryInterface
{
    public function paginateWithFilters(array $filters, int $perPage = 20): LengthAwarePaginator
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

    public function createForUser(User $user, array $attributes): Topic
    {
        return $user->topics()->create([
            'title' => $attributes['title'],
            'body' => $attributes['body'],
            'series_id' => $attributes['series_id'] ?? null,
        ]);
    }

    public function loadForShow(Topic $topic): Topic
    {
        return $topic->load([
            'user',
            'series',
            'rootComments.user',
            'rootComments.replies.user',
        ]);
    }
}
