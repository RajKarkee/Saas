<?php

namespace App\Http\Requests\Admin;

use App\Models\Restaurant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateMenuCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $restaurantId = Restaurant::where('owner_id', Auth::id())->value('id');
        $categoryId = $this->route('id');

        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'position' => [
                'nullable',
                'integer',
                'min:1',
                Rule::unique('menu_categories', 'position')
                    ->where(fn ($query) => $query->where('restaurant_id', $restaurantId))
                    ->ignore($categoryId),
            ],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
