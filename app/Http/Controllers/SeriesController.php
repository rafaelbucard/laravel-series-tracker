<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSeriesRequest;
use App\Http\Requests\UpdateSeriesRequest;
use App\Models\Series;
use App\Models\StreamingService;
use App\Services\External\OmdbClient;
use App\Services\SeriesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class SeriesController extends Controller
{
    public function __construct(
        private readonly SeriesService $seriesService,
        private readonly OmdbClient $omdb,
    ) {
        //
    }

    public function index(Request $request): View
    {
        $series = $request->user()
            ->series()
            ->with('streamingService')
            ->withCount(['seasons', 'episodes as episodes_total', 'episodes as episodes_watched' => function ($q) {
                $q->where('watched', true);
            }])
            ->orderBy('name')
            ->get();

        return view('series.index', [
            'series' => $series,
        ]);
    }

    public function create(): View
    {
        return view('series.create', [
            'streamingServices' => StreamingService::orderBy('name')->get(),
            'omdbConfigured' => $this->omdb->isConfigured(),
        ]);
    }

    public function store(StoreSeriesRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['cover_file'] = $request->file('cover_file');

            $series = $this->seriesService->create($request->user(), $data);

            return redirect()
                ->route('series.index')
                ->with('status', "Série criada com sucesso: {$series->name}");
        } catch (\Throwable $e) {
            Log::error('Failed to create series', ['error' => $e->getMessage()]);

            return back()
                ->withInput()
                ->with('error', 'Houve um erro ao criar a série. Tente novamente.');
        }
    }

    public function edit(Series $series): View
    {
        $this->authorize('update', $series);

        return view('series.edit', [
            'series' => $series,
            'streamingServices' => StreamingService::orderBy('name')->get(),
            'omdbConfigured' => $this->omdb->isConfigured(),
        ]);
    }

    public function update(UpdateSeriesRequest $request, Series $series): RedirectResponse
    {
        $this->authorize('update', $series);

        $data = $request->validated();
        $data['cover_file'] = $request->file('cover_file');

        $this->seriesService->update($series, $data);

        return redirect()
            ->route('series.index')
            ->with('status', "Série atualizada: {$series->fresh()->name}");
    }

    public function destroy(Series $series): RedirectResponse
    {
        $this->authorize('delete', $series);

        $name = $series->name;
        $this->seriesService->delete($series);

        return redirect()
            ->route('series.index')
            ->with('status', "Série removida: {$name}");
    }

    public function omdbSearch(Request $request): JsonResponse
    {
        $query = (string) $request->query('q', '');

        if (! $this->omdb->isConfigured()) {
            return response()->json([
                'configured' => false,
                'results' => [],
            ]);
        }

        return response()->json([
            'configured' => true,
            'results' => $this->omdb->searchByTitle($query),
        ]);
    }
}
