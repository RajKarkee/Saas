<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRestaurantStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $staffId = (int) $this->route('id');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('staff', 'email')->ignore($staffId)],
            'password' => ['nullable', 'string', 'min:8', 'max:255'],
            'phone' => ['nullable', 'regex:/^\+977-9\d{9}$/'],
            'role' => ['required', 'string', 'in:Manager,Delivery Person,Staff'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'photo' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
