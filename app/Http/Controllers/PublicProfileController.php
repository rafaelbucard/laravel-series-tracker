<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PublicProfileController extends Controller
{
    public function show(Request $request, User $user): View
    {
        if (! $user->profile_is_public && $request->user()?->id !== $user->id) {
            throw new NotFoundHttpException;
        }

        $series = $user->series()
            ->with('streamingService')
            ->withCount(['seasons', 'episodes as episodes_total', 'episodes as episodes_watched' => function ($q) {
                $q->where('watched', true);
            }])
            ->orderBy('name')
            ->get();

        $stats = [
            'series_count' => $series->count(),
            'episodes_watched' => $series->sum('episodes_watched'),
            'topics_count' => $user->topics()->count(),
            'comments_count' => $user->comments()->count(),
        ];

        return view('users.show', [
            'profileUser' => $user,
            'series' => $series,
            'stats' => $stats,
        ]);
    }
}
