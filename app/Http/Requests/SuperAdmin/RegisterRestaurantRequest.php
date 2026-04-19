<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRestaurantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:restaurants,email'],
            'domain' => ['required', 'string', 'max:255', 'unique:restaurants,domain'],
            'subdomain' => ['nullable', 'string', 'max:255', 'unique:restaurants,subdomain'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
