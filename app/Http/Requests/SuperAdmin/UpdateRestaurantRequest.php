<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRestaurantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $restaurantId = (int) $this->route('id');

        return [
            'name' => ['required', 'string', 'max:255'],
            'domain' => ['required', 'string', 'max:255', 'unique:restaurants,domain,' . $restaurantId],
            'subdomain' => ['required', 'string', 'max:255', 'unique:restaurants,subdomain,' . $restaurantId],
            'owner_id' => ['required', 'exists:admins,id'],
            'status' => ['required', 'in:active,inactive,pending'],
        ];
    }
}
