<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\User;
use App\Notifications\ResetPasswordQueued;
use Illuminate\Support\Facades\Notification;

class PasswordResetTest extends TestCase
{
    public function test_reset_password_link_screen_can_be_rendered()
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested()
    {
        Notification::fake();

        /** @var \App\Models\Branch $branch */
        $branch = Branch::factory()->create();

        /** @var \App\Models\User $user */
        $user = User::factory()->for($branch)->create();

        $this->post(route('password.request'), ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPasswordQueued::class);
    }

    public function test_reset_password_screen_can_be_rendered()
    {
        Notification::fake();

        /** @var \App\Models\Branch $branch */
        $branch = Branch::factory()->create();

        /** @var \App\Models\User $user */
        $user = User::factory()->for($branch)->create();

        $this->post(route('password.request'), ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPasswordQueued::class, function ($notification) {
            $response = $this->get(route('password.reset', ['token' => $notification->token]));

            $response->assertStatus(200);

            return true;
        });
    }

    public function test_password_can_be_reset_with_valid_token()
    {
        Notification::fake();

        /** @var \App\Models\Branch $branch */
        $branch = Branch::factory()->create();

        /** @var \App\Models\User $user */
        $user = User::factory()->for($branch)->create();

        $this->post(route('password.request'), ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPasswordQueued::class, function ($notification) use ($user) {
            $response = $this->post(route('password.update'), [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response->assertSessionHasNoErrors();

            return true;
        });
    }
}
