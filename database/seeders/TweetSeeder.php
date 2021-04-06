<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tweet;
use App\Models\User;

class TweetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $user = User::first();
        Tweet::factory(3)->create(['user_id'=>$user->id]);
        Tweet::factory(10)->create();
    }
}
