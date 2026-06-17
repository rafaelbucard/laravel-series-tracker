<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSeriesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:200'],
            'streaming_service_id' => ['nullable', 'integer', 'exists:streaming_services,id'],
            'synopsis' => ['nullable', 'string', 'max:5000'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'imdb_id' => ['nullable', 'string', 'max:32'],
            'qt_seasons' => ['required', 'integer', 'min:1', 'max:50'],
            'qt_episodes' => ['required', 'integer', 'min:1', 'max:500'],
            'cover_file' => ['nullable', 'image', 'max:5120'],
            'cover_url' => ['nullable', 'url', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'name.min' => 'O nome precisa ter pelo menos 2 caracteres.',
            'qt_seasons.min' => 'Informe pelo menos 1 temporada.',
            'qt_episodes.min' => 'Informe pelo menos 1 episódio por temporada.',
            'streaming_service_id.exists' => 'O serviço de streaming selecionado é inválido.',
            'cover_file.image' => 'O arquivo precisa ser uma imagem.',
            'cover_file.max' => 'A imagem precisa ter no máximo 5MB.',
            'cover_url.url' => 'Informe uma URL válida para a imagem.',
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
            'qt_seasons' => 'quantidade de temporadas',
            'qt_episodes' => 'episódios por temporada',
            'cover_file' => 'capa',
            'cover_url' => 'URL da capa',
        ];
    }
}
