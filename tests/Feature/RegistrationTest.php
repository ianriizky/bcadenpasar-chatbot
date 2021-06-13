<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\VerifyEmailQueued;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Notification;

class RegistrationTest extends TestCase
{
    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
    }

    public function test_new_users_can_register()
    {
        Notification::fake();

        $user = User::factory()->raw();

        $response = $this->post(route('register'), [
            'username' => $user['username'],
            'fullname' => $user['fullname'],
            'gender' => $user['gender'],
            'email' => $user['email'],
            'phone_country' => $user['phone_country'],
            'phone' => $user['phone'],
            'password' => $user['password'],
            'password_confirmation' => $user['password'],
            'agree_with_terms' => true,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);

        Notification::assertSentTo(User::firstWhere('username', $user['username']), VerifyEmailQueued::class);
    }
}
