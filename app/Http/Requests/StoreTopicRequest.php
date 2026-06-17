<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTopicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:5', 'max:120'],
            'body' => ['required', 'string', 'min:10', 'max:5000'],
            'series_id' => ['nullable', 'integer', 'exists:series,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'título',
            'body' => 'mensagem',
            'series_id' => 'série',
        ];
    }
}
