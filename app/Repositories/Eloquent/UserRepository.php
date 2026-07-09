<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $attributes): User
    {
        return User::create($attributes);
    }

    public function countTopics(User $user): int
    {
        return $user->topics()->count();
    }

    public function countComments(User $user): int
    {
        return $user->comments()->count();
    }
}
