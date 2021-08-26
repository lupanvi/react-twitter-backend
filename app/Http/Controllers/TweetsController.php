<?php

namespace App\Http\Controllers;

use App\Http\Resources\TweetResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

/**
 * @group Tweets management  
 *
 * Api endpoints for tweets
 */
class TweetsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @response 200{
     *           
     *  "data": [
     *      {
     *         "id": 1,
     *          "content": "Tweet body",
     *          "image_path": "avatar.png",
     *          "created_at": "2020-05-25T06:21:47.000000Z",
     *          "user": {
     *                 "id" => 1,
     *                  "name" => "Test",
     *                  "verified" => true,
     *                  "username" => test,
     *                  "avatar_path" => "avatar.png"
     *           }
     *      },
     *      {
     *         "id": 2,
     *          "content": "Tweet body 2",
     *          "image_path": "avatar.png",
     *          "created_at": "2020-05-25T06:21:47.000000Z",
     *          "user": {
     *                 "id" => 2,
     *                  "name" => "Test 2",
     *                  "verified" => false,
     *                  "username" => test2,
     *                  "avatar_path" => "avatar2.png"
     *           }
     *      }
     *      
     * 
     *  ],
     *  "links": {
     *      "first": "http://localhost:8000/api/tweets?page=1"
     *       "last": "http://localhost:8000/api/tweets?page=2"
     *       "next": "http://localhost:8000/api/tweets?page=2"
     *       "prev": null
     *  },
     *  "meta": {
     *     current_page: 1
     *     from: 1
     *     last_page: 2     
     *     path: "http://localhost:8000/api/tweets"
     *     per_page: 10
     *     to: 10
     *     total: 16
     *  }
     * 
     * }
     *  
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TweetResource::collection(
            Cache::remember('tweets', 60*60*24, function () {
                return Tweet::with('user')->latest()->paginate(10);
            })
        );        
    }   

    /**
     * Store a newly created resource in storage.
     * 
     * 
     * @bodyParam content string required The body of the tweet. Example: this is a tweet
     * @bodyParam image_path binary optional The image of the tweet. Example: myimage.jpg
     * 
     * @response 200 {
     *     "id": 1,
     *     "content": "Tweet body",
     *     "image_path": "avatar.png",
     *     "created_at": 2020-05-25T06:21:47.000000Z,
     *     "user": {
     *          "id" => 1,
     *           "name" => "Test",
     *           "verified" => true,
     *           "username" => test,
     *           "avatar_path" => "avatar.png"
     *      }
     * }
     * 
     * @response status=422 scenario="Validation error" {
     *    "message": "The given data was invalid.",
     *    "errors": {
     *        "content": [
     *            "The content field is required."
     *        ]
     *    }
     * }
     *     
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        request()->validate([
            'content' => 'required'            
        ]);        

        $tweet = auth()->user()->tweets()->create([
            'content' => request('content'),
            'image_path' => request('image_path') 
                ? request()->file('image_path')->store('tweets', 'public') 
                : null
        ]);

        Cache::forget('tweets');

        return new TweetResource($tweet->load('user'));
    }

    /** 
     * Search for a given user in elastic search
     * with Laravel scout methods
     * 
     * @bodyParam search_term string required The search term. Example: test
     * 
     * @response 200 [
     *   {
     *       "id": 10040,
     *       "name": "williamtest",
     *       "verified": false,
     *       "username": "test.tillman",
     *       "avatar_path": "https://via.placeholder.com/640x480.png/00ccff?text=et"
     *   },
     *   {
     *       "id": 10026,
     *       "name": "Tess Schaden",
     *       "verified": false,
     *       "username": "marlon.lueilwitz",
     *       "avatar_path": "https://via.placeholder.com/640x480.png/002255?text=vom"
     *   }
     * ]
     * 
     * @return \Illuminate\Http\Response
     */
    public function search()
    {  
        
        request()->validate([
            'search_term' => 'required'            
        ]); 
              
        //it searches for name and username, fields are defined in the User Model
        $searchResult = User::search(request('search_term'))->take(5)->get();

        return UserResource::collection($searchResult);

    }


    /** 
     * Search for a given user in elastic search with prefixes
     * using elastic-scout-driver-plus
     * 
     * @bodyParam search_term string required The search term. Example: test
     * 
     * @response 200 [
     *   {
     *       "id": 10040,
     *       "name": "williamtest",
     *       "verified": false,
     *       "username": "test.tillman",
     *       "avatar_path": "https://via.placeholder.com/640x480.png/00ccff?text=et"
     *   },
     *   {
     *       "id": 10026,
     *       "name": "Tess Schaden",
     *       "verified": false,
     *       "username": "marlon.lueilwitz",
     *       "avatar_path": "https://via.placeholder.com/640x480.png/002255?text=vom"
     *   }
     * ]
     * @codeCoverageIgnore
     * @return \App\Http\Resources\UserResource
     */
    public function searchWithPrefix()
    {  
        
        request()->validate([
            'search_term' => 'required'
        ]);              
        
        $searchResult = User::prefixSearch()
            ->field('username')
            ->value(request('search_term'))            
            ->size(5)            
            ->execute();        

        return UserResource::collection($searchResult->models());

    }
  
}
