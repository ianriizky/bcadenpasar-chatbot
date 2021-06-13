<?php

namespace Tests\Feature;

use App\Models\User;

class AuthenticationTest extends TestCase
{
    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen_using_email()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->post(route('login'), [
            'identifier' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));
    }

    public function test_users_can_authenticate_using_the_login_screen_using_phone()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->post(route('login'), [
            'identifier' => $user->phone,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));
    }

    public function test_users_can_authenticate_using_the_login_screen_using_username()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->post(route('login'), [
            'identifier' => $user->username,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}
