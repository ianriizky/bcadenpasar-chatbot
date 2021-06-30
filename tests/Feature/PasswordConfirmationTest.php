<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\User;

class PasswordConfirmationTest extends TestCase
{
    use Concerns\HandleAuthentication;

    public function test_confirm_password_screen_can_be_rendered()
    {
        $user = $this->createUserFromFactory();

        $response = $this->actingAs($user)->get(route('password.confirm'));

        $response->assertStatus(200);
    }

    public function test_password_can_be_confirmed()
    {
        $user = $this->createUserFromFactory();

        $response = $this->actingAs($user)->post(route('password.confirm'), [
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_password_is_not_confirmed_with_invalid_password()
    {
        $user = $this->createUserFromFactory();

        $response = $this->actingAs($user)->post(route('password.confirm'), [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
    }
}
