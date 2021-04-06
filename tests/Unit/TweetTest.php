<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tweet;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TweetTest extends TestCase
{
	use RefreshDatabase;

    public function test_it_belongs_a_user()
    {
        $tweet = Tweet::factory()->create();
        $this->assertInstanceOf(User::class, $tweet->user);
    }
}
