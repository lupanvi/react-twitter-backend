<?php

namespace Tests\Unit;

use Tests\TestCase;
use \App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
	use RefreshDatabase;
    
    public function test_a_user_has_tweets()
    {

        $user = User::factory()->create();
        $this->assertInstanceOf(Collection::class, $user->tweets);

    }

}
