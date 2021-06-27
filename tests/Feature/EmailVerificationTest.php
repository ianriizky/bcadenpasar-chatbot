<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;

class EmailVerificationTest extends TestCase
{
    public function test_email_verification_screen_can_be_rendered()
    {
        /** @var \App\Models\Branch $branch */
        $branch = Branch::factory()->create();

        /** @var \App\Models\User $user */
        $user = User::factory()->for($branch)->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get(route('verification.notice'));

        $response->assertStatus(200);
    }

    public function test_email_can_be_verified()
    {
        Event::fake();

        /** @var \App\Models\Branch $branch */
        $branch = Branch::factory()->create();

        /** @var \App\Models\User $user */
        $user = User::factory()->for($branch)->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect(route('verification.success'));
    }

    public function test_email_is_not_verified_with_invalid_hash()
    {
        /** @var \App\Models\Branch $branch */
        $branch = Branch::factory()->create();

        /** @var \App\Models\User $user */
        $user = User::factory()->for($branch)->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email')]
        );

        $this->actingAs($user)->get($verificationUrl);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }
}
