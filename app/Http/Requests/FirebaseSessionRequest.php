<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FirebaseSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return ! auth()->check();
    }

    public function rules(): array
    {
        return [
            'id_token' => ['required', 'string', 'min:100'],
        ];
    }
}
