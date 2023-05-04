<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'text' => 'required|string|min:50|max:400',
            'rating' => 'required|integer|min:1|max:10',
        ];
    }
}
