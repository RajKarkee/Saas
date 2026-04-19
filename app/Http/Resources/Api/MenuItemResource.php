<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'restaurant_id' => $this->restaurant_id,
            'menu_category_id' => $this->menu_category_id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'is_available' => $this->is_available,
            'stock_quantity' => $this->stock_quantity,
        ];
    }
}
