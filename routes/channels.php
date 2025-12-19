<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;

// Use staff guard for private channel authentication
Broadcast::routes(['middleware' => ['web', 'auth:staff']]);

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('delivery-man.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('restaurant.{restaurantId}.staff', function ($user, $restaurantId) {
    return $user->role===1 && (int) $user->restaurant_id === (int) $restaurantId;
});
