<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTopicRequest;
use App\Models\Series;
use App\Models\Topic;
use App\Services\TopicService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TopicController extends Controller
{
    public function __construct(
        private readonly TopicService $topics,
    ) {
        //
    }

    public function index(Request $request): View
    {
        $topics = $this->topics->list([
            'series_id' => $request->query('series_id'),
            'sort' => $request->query('sort'),
        ]);

        return view('community.index', [
            'topics' => $topics,
            'sort' => $request->query('sort', 'recent'),
            'seriesFilter' => $request->query('series_id'),
        ]);
    }

    public function create(Request $request): View
    {
        return view('community.create', [
            'userSeries' => $request->user()->series()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(StoreTopicRequest $request): RedirectResponse
    {
        $topic = $this->topics->create($request->user(), $request->validated());

        return redirect()
            ->route('community.topics.show', $topic)
            ->with('status', 'Tópico criado com sucesso!');
    }

    public function show(Topic $topic): View
    {
        $topic->load([
            'user',
            'series',
            'rootComments.user',
            'rootComments.replies.user',
        ]);

        return view('community.show', [
            'topic' => $topic,
        ]);
    }
}
