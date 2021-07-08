<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use App\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Crypt;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get('/verify-email');

        $response->assertStatus(200);
    }

    public function test_email_can_be_verified()
    {
        Event::fake();

        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
       
        $hash =  Crypt::encrypt($user->getKey());        

        $verificationUrl = route('verification.verify', ['hash'=>$hash]);           

        $response = $this->actingAs($user)->getJson($verificationUrl);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());        
        $response            
            ->assertJson([
                'message' => 'verified',
            ]);

    }

    public function test_email_is_not_verified_with_invalid_hash()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $hash =  Crypt::encrypt($user->getKey());        

        $verificationUrl = route('verification.verify', ['hash'=>'invalid hash']);   

        $this->actingAs($user)->getJson($verificationUrl);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }
}
