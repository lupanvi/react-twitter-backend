<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Tweet;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TweetsTest extends TestCase
{
    
    use RefreshDatabase;

    public function test_guest_cannot_manage_tweets()
    {

        //Get the list of tweets        
        $this->json('GET', '/api/tweets')->assertStatus(401); 

        //create tweets                            
        $this->json('POST', '/api/tweets', [])->assertStatus(401);               

    }

    public function test_a_user_can_get_the_tweets_with_their_owners()
    {
        $this->signIn();         
        $user = User::factory()->create();
        $tweet = Tweet::factory()->create(['user_id'=>$user->id]);                

        $user2 = User::factory()->create();
        $tweet2 = Tweet::factory()->create(['user_id'=>$user2->id]);                

        $data = ['data'=> [
                    [
                        'content' => $tweet->content,
                        'image_path' => $tweet->image_path,
                        'user' => [
                            'name' => $user->name,
                            'username' => $user->username,
                            'verified' => $user->verified                
                        ]
                    ],
                    [
                        'content' => $tweet2->content,
                        'image_path' => $tweet2->image_path,
                        'user' => [
                            'name' => $user2->name,
                            'username' => $user2->username,
                            'verified' => $user2->verified                
                        ]
                    ]
            ]
        ];

        $response = $this->get('/api/tweets')
                ->assertStatus(200)
                ->assertJson($data);

        $this->assertCount(2, $response->json()['data']);
      
    }

    public function test_a_user_can_create_tweets(){

        $user = $this->signIn(); 
        $attributes = ['content'=>'test'];

        $response = $this->post('/api/tweets', $attributes)->json();                

        $this->assertDatabaseHas('tweets', [
            'content' => $attributes['content'],
            'user_id' => $user->id
        ]);        

    }

    /** @test */
    public function test_a_tweet_requires_a_content(){

        $this->signIn();

        $attributes = Tweet::factory()->raw(['content'=>'']);          

        $this->post('/api/tweets', $attributes)->assertSessionHasErrors('content');
    }

    /** @test */
    public function test_a_user_can_upload_an_image_as_part_of_a_tweet(){

        $user = $this->signIn();
        Storage::fake('public');

        $attributes = Tweet::factory()->raw(); 
        $attributes['image_path'] = UploadedFile::fake()->image('image.jpg'); 

        $response = $this->json('POST','/api/tweets', $attributes )->json();                

        $this->assertDatabaseHas('tweets', [
            'content'=>$attributes['content'],            
            'user_id' => $user->id
        ]);
        
        Storage::disk('public')->assertExists( 'tweets/' . $attributes['image_path']->hashName());
        
    }

    /** @test */
    public function test_a_tweet_requires_a_valid_image(){

        $this->signIn();

        $this->json('POST', '/api/tweets' , [
            'image_path' => 'not-an-image'
        ])->assertStatus(422);

    }

    public function test_search_users_by_name()
    {
        $this->signIn();
        $search_term = 'Luis';
        $user = User::factory()->create(['name'=>$search_term]);

        $response = $this->json('POST', '/api/tweets/search', [
            'search_term' => $search_term
        ]);

        $response->assertJson([['name'=>$search_term]]);

    }

    public function test_search_users_by_username()
    {
        $this->signIn();
        $search_term = 'luis2021';
        $user = User::factory()->create(['username'=>$search_term]);

        $response = $this->json('POST', '/api/tweets/search', [
            'search_term' => $search_term
        ]);

        $response->assertJson([['username'=>$search_term]]);

    }

    
}
