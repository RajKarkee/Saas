<?php

namespace App\Http\Requests\Landing;

use Illuminate\Foundation\Http\FormRequest;

class RegisterAdminRestaurantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data' => ['required', 'array'],
            'data.admin' => ['required', 'array'],
            'data.admin.username' => ['required', 'string', 'max:255'],
            'data.admin.email' => ['required', 'email', 'max:255', 'unique:admins,email'],
            'data.admin.password' => ['required', 'string', 'min:8', 'confirmed'],
            'data.restaurant' => ['required', 'array'],
            'data.restaurant.name' => ['required', 'string', 'max:255'],
            'data.restaurant.email' => ['required', 'email', 'max:255', 'unique:restaurant_settings,email'],
            'data.restaurant.domain' => ['nullable', 'string', 'max:255', 'unique:restaurants,domain'],
            'data.restaurant.subdomain' => ['nullable', 'string', 'max:255', 'unique:restaurants,subdomain'],
        ];
    }
}
