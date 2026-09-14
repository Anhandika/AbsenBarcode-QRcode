<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScanAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canScan() ?? false;
    }

    public function rules(): array
    {
        return [
            'qr_token' => ['required', 'string', 'min:20', 'max:100'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'accuracy' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
