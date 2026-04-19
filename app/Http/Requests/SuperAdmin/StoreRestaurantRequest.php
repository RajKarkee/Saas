<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;

class StoreRestaurantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'domain' => ['required', 'string', 'max:255', 'unique:restaurants,domain'],
            'subdomain' => ['required', 'string', 'max:255', 'unique:restaurants,subdomain'],
            'owner_id' => ['required', 'exists:admins,id'],
            'status' => ['required', 'in:active,inactive,pending'],
        ];
    }
}
