<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyncSeasonsRequest extends FormRequest
{
    protected $errorBag = 'seasons';

    public function authorize(): bool
    {
        $series = $this->route('series');

        return $this->user() && $series && $this->user()->can('update', $series);
    }

    public function rules(): array
    {
        return [
            'seasons' => ['required', 'array', 'min:1'],
            'seasons.*.id' => ['nullable', 'integer'],
            'seasons.*.episodes' => ['required', 'integer', 'min:1', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'seasons.required' => 'Informe pelo menos 1 temporada.',
            'seasons.min' => 'Informe pelo menos 1 temporada.',
            'seasons.*.episodes.required' => 'Informe a quantidade de episódios da temporada.',
            'seasons.*.episodes.min' => 'Cada temporada precisa ter pelo menos 1 episódio.',
            'seasons.*.episodes.max' => 'Uma temporada pode ter no máximo 500 episódios.',
        ];
    }
}
