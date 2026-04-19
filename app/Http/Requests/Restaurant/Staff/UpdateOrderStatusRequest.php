<?php

namespace App\Http\Requests\Restaurant\Staff;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'status' => ['required', 'string', Rule::in(['pending', 'accepted', 'cooking', 'cooked', 'completed', 'cancelled'])],
        ];
    }
}
