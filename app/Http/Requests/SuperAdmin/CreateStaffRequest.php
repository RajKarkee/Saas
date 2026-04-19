<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;

class CreateStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:225'],
            'email' => ['required', 'string', 'email', 'max:225', 'unique:staff,email'],
            'phone' => ['nullable', 'regex:/^\+977-9\d{9}$/'],
            'role' => ['required', 'integer'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }
}
