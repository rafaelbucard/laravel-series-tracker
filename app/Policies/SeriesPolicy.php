<?php

namespace App\Policies;

use App\Models\Series;
use App\Models\User;

class SeriesPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Series $series): bool
    {
        return $user->id === $series->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Series $series): bool
    {
        return $user->id === $series->user_id;
    }

    public function delete(User $user, Series $series): bool
    {
        return $user->id === $series->user_id;
    }
}
