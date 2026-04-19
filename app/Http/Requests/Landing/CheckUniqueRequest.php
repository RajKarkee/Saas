<?php

namespace App\Http\Requests\Landing;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckUniqueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(['admin_email', 'restaurant_domain', 'restaurant_email'])],
            'value' => ['required', 'string', 'max:255'],
        ];
    }
}
