<?php

namespace Database\Seeders;

use App\Models\Tweet;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {                

        User::factory()->create([
            'name' => 'Demo',                    
            'email' => 'demo@gmail.com',
            'username' => 'demo',
            'avatar_path' => 'default_avatar.png',
            'verified' => true,
            'password' => bcrypt('password')
        ]);

        User::factory(9)->create();
    }
}
