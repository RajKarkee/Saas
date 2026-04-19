<?php

namespace App\Http\Requests\Restaurant\Staff;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateDeliveryProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('staff')->check();
    }

    public function rules(): array
    {
        $staffId = Auth::guard('staff')->id();

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('staff', 'email')->ignore($staffId)],
            'phone' => ['nullable', 'regex:/^\+977-9\d{9}$/'],
            'password' => ['nullable', 'string', 'min:8'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'remove_photo' => ['nullable', 'in:0,1'],
        ];
    }
}
