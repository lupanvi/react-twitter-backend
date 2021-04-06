<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;    

    protected function signIn($user = null)
    {        

        $user = $user ?: User::factory()->create();
        Sanctum::actingAs($user);        
        return $user;
    }
}
