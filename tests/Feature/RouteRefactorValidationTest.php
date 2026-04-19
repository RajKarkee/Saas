<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RouteRefactorValidationTest extends TestCase
{
    public function test_split_route_names_are_registered(): void
    {
        $this->assertTrue(Route::has('kitchen.login'));
        $this->assertTrue(Route::has('admin.profile.update'));
        $this->assertTrue(Route::has('restaurant.delivery.profile.update'));
        $this->assertTrue(Route::has('restaurant.staff.setting.update'));
    }

    public function test_kitchen_login_page_is_accessible(): void
    {
        $response = $this->get('/kitchen/login');

        $response->assertOk();
    }

    public function test_kitchen_login_requires_email_and_password(): void
    {
        $response = $this->from('/kitchen/login')->post('/kitchen/login', []);

        $response->assertRedirect('/kitchen/login');
        $response->assertSessionHasErrors(['email', 'password']);
    }
}
