<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdminWithRestaurantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'owner_name' => ['required', 'string', 'max:255'],
            'owner_email' => ['required', 'email', 'max:255', 'unique:admins,email'],
            'owner_password' => ['required', 'string', 'min:8'],
            'owner_status' => ['required', Rule::in(['active', 'inactive', 'pending'])],
            'owner_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            'res_name' => ['required', 'string', 'max:255'],
            'res_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            'domain' => ['required', 'string', 'max:255', 'unique:restaurants,domain'],
            'subdomain' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['active', 'inactive', 'pending'])],
        ];
    }
}
