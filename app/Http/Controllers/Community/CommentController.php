<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Topic;
use App\Services\CommentService;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function __construct(
        private readonly CommentService $comments,
    ) {
        //
    }

    public function store(StoreCommentRequest $request, Topic $topic): RedirectResponse
    {
        $this->comments->create($request->user(), $topic, $request->validated());

        return redirect()
            ->route('community.topics.show', $topic)
            ->with('status', 'Comentário enviado!');
    }
}
