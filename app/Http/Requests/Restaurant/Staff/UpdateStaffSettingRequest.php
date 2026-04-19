<?php

namespace App\Http\Requests\Restaurant\Staff;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateStaffSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $staffId = Auth::guard('staff')->id();

        return [
            'name' => ['required', 'string', 'max:225'],
            'email' => ['required', 'string', 'email', 'max:225', Rule::unique('staff', 'email')->ignore($staffId)],
            'phone' => ['nullable', 'regex:/^\+977-9\d{9}$/'],
            'password' => ['nullable', 'string', 'min:8'],
        ];
    }
}
