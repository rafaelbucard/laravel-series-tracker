<?php

use App\Services\External\OmdbClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Cache::flush();
});

it('returns empty array when not configured', function () {
    $client = new OmdbClient(apiKey: null);

    expect($client->searchByTitle('Loki'))->toBe([]);
    expect($client->isConfigured())->toBeFalse();
});

it('searches by title and maps response fields', function () {
    Http::fake([
        'www.omdbapi.com/*' => Http::response([
            'Response' => 'True',
            'Search' => [
                [
                    'Title' => 'Loki',
                    'Year' => '2021–',
                    'imdbID' => 'tt9140554',
                    'Poster' => 'https://img.test/loki.jpg',
                ],
                [
                    'Title' => 'No Poster',
                    'Year' => '2020',
                    'imdbID' => 'tt000',
                    'Poster' => 'N/A',
                ],
            ],
        ], 200),
    ]);

    $client = new OmdbClient(apiKey: 'test-key', baseUrl: 'https://www.omdbapi.com');

    $results = $client->searchByTitle('Loki');

    expect($results)->toHaveCount(2)
        ->and($results[0])->toMatchArray([
            'title' => 'Loki',
            'year' => '2021–',
            'imdb_id' => 'tt9140554',
            'poster' => 'https://img.test/loki.jpg',
        ])
        ->and($results[1]['poster'])->toBeNull();
});

it('returns null on findByImdbId failure response', function () {
    Http::fake([
        '*' => Http::response(['Response' => 'False', 'Error' => 'Not Found!'], 200),
    ]);

    $client = new OmdbClient(apiKey: 'test-key', baseUrl: 'https://www.omdbapi.com');

    expect($client->findByImdbId('tt0000000'))->toBeNull();
});
