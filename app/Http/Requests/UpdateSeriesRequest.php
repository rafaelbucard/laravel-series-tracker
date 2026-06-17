<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSeriesRequest extends FormRequest
{
    public function authorize(): bool
    {
        $series = $this->route('series');

        return $this->user() && $series && $this->user()->can('update', $series);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:200'],
            'streaming_service_id' => ['nullable', 'integer', 'exists:streaming_services,id'],
            'synopsis' => ['nullable', 'string', 'max:5000'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'imdb_id' => ['nullable', 'string', 'max:32'],
            'cover_file' => ['nullable', 'image', 'max:5120'],
            'cover_url' => ['nullable', 'url', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'streaming_service_id' => 'serviço de streaming',
            'synopsis' => 'sinopse',
            'year' => 'ano',
            'imdb_id' => 'ID do IMDB',
            'cover_file' => 'capa',
            'cover_url' => 'URL da capa',
        ];
    }
}
