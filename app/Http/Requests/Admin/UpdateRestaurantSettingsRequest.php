<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRestaurantSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'regex:/^\+977-9\d{9}$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'map_url' => ['nullable', 'url', 'max:2048'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
