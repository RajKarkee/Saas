<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Restaurant;

class UpdateRestaurantProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $restaurantId = Restaurant::where('owner_id', auth('admin')->id())->value('id');

        return [
            'name' => ['required', 'string', 'max:255'],
            'domain' => ['required', 'string', 'max:255', 'unique:restaurants,domain,' . $restaurantId],
            'subdomain' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}
