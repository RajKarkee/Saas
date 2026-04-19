<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuItemAddonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'restaurant_id' => $this->restaurant_id,
            'menu_item_id' => $this->menu_item_id,
            'name' => $this->name,
            'additional_price' => $this->additional_price,
            'is_available' => $this->is_available,
        ];
    }
}
