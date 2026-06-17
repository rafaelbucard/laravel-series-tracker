<?php

namespace App\Services\External;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OmdbClient
{
    public function __construct(
        private readonly ?string $apiKey = null,
        private readonly ?string $baseUrl = null,
    ) {
        //
    }

    public function searchByTitle(string $title, int $page = 1): array
    {
        $title = trim($title);

        if ($title === '' || ! $this->isConfigured()) {
            return [];
        }

        $cacheKey = 'omdb:search:'.md5(strtolower($title).':'.$page);

        return Cache::remember($cacheKey, now()->addHour(), function () use ($title, $page) {
            $response = Http::baseUrl($this->resolveBaseUrl())
                ->acceptJson()
                ->get('/', [
                    's' => $title,
                    'type' => 'series',
                    'page' => $page,
                    'apikey' => $this->resolveApiKey(),
                ]);

            if (! $response->ok()) {
                Log::warning('OMDB search failed', ['status' => $response->status()]);
                return [];
            }

            $data = $response->json();

            if (($data['Response'] ?? 'False') !== 'True') {
                return [];
            }

            return array_map(fn ($item) => [
                'title' => $item['Title'] ?? null,
                'year' => $item['Year'] ?? null,
                'imdb_id' => $item['imdbID'] ?? null,
                'poster' => ($item['Poster'] ?? 'N/A') !== 'N/A' ? $item['Poster'] : null,
            ], $data['Search'] ?? []);
        });
    }

    public function findByImdbId(string $imdbId): ?array
    {
        $imdbId = trim($imdbId);

        if ($imdbId === '' || ! $this->isConfigured()) {
            return null;
        }

        return Cache::remember('omdb:imdb:'.$imdbId, now()->addDay(), function () use ($imdbId) {
            $response = Http::baseUrl($this->resolveBaseUrl())
                ->acceptJson()
                ->get('/', [
                    'i' => $imdbId,
                    'apikey' => $this->resolveApiKey(),
                ]);

            if (! $response->ok()) {
                return null;
            }

            $data = $response->json();

            if (($data['Response'] ?? 'False') !== 'True') {
                return null;
            }

            return [
                'title' => $data['Title'] ?? null,
                'year' => isset($data['Year']) ? (int) substr($data['Year'], 0, 4) : null,
                'plot' => ($data['Plot'] ?? 'N/A') !== 'N/A' ? $data['Plot'] : null,
                'imdb_id' => $data['imdbID'] ?? null,
                'poster' => ($data['Poster'] ?? 'N/A') !== 'N/A' ? $data['Poster'] : null,
                'total_seasons' => isset($data['totalSeasons']) && $data['totalSeasons'] !== 'N/A'
                    ? (int) $data['totalSeasons']
                    : null,
            ];
        });
    }

    public function isConfigured(): bool
    {
        return ! empty($this->resolveApiKey());
    }

    private function resolveApiKey(): ?string
    {
        return $this->apiKey ?? config('services.omdb.key');
    }

    private function resolveBaseUrl(): string
    {
        return $this->baseUrl ?? config('services.omdb.base_url', 'https://www.omdbapi.com');
    }
}
