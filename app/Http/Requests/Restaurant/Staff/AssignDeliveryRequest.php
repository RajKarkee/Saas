<?php

namespace App\Http\Requests\Restaurant\Staff;

use Illuminate\Foundation\Http\FormRequest;

class AssignDeliveryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'delivery_person_id' => ['required', 'integer', 'exists:staff,id'],
        ];
    }
}
