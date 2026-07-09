<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\Contracts\SeriesRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PublicProfileController extends Controller
{
    public function __construct(
        private readonly SeriesRepositoryInterface $series,
        private readonly UserRepositoryInterface $users,
    ) {
        //
    }

    public function show(Request $request, User $user): View
    {
        if (! $user->profile_is_public && $request->user()?->id !== $user->id) {
            throw new NotFoundHttpException;
        }

        $series = $this->series->listForUserWithCounts($user);

        $stats = [
            'series_count' => $series->count(),
            'episodes_watched' => $series->sum('episodes_watched'),
            'topics_count' => $this->users->countTopics($user),
            'comments_count' => $this->users->countComments($user),
        ];

        return view('users.show', [
            'profileUser' => $user,
            'series' => $series,
            'stats' => $stats,
        ]);
    }
}
