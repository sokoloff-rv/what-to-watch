<?php

namespace App\Http\Requests;

use App\Models\Film;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFilmRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'imdb_id' => [
                'required',
                'string',
                Rule::unique(Film::class),
                'regex:/^tt\d{7,}$/i',
            ],
        ];
    }
}
