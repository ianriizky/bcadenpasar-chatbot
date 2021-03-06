<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\User;
use App\Notifications\VerifyEmailQueued;
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

        /** @var \App\Models\Branch $branch */
        $branch = Branch::factory()->create();

        $user = User::factory()->raw();

        $response = $this->post(route('register'), [
            'branch_name' => $branch->name,
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
        $response->assertRedirect(route('verification.notice'));

        Notification::assertSentTo(User::firstWhere('username', $user['username']), VerifyEmailQueued::class);
    }
}
