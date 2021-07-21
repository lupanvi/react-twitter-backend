<?php

namespace App\Http\Controllers;

use App\Http\Resources\TweetResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class TweetsController extends Controller
{
    /**
     * Display a listing of the resource.
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
     * @return \Illuminate\Http\Response
     */
    public function search()
    {  
        
        request()->validate([
            'search_term' => 'required'            
        ]); 
              
        //it searches for name and username, fields are defined in the User Model
        $searchResult = User::search(request('search_term'))->paginate(5);

        return UserResource::collection($searchResult);

    }


    /** 
     * Search for a given user in elastic search with prefixes
     * using elastic-scout-driver-plus
     * 
     * @return \Illuminate\Http\Response
     */
    public function search_with_prefix()
    {  
        
        request()->validate([
            'search_term' => 'required'
        ]);              
        
        $searchResult = User::prefixSearch()
            ->fields('name')
            ->value(request('search_term'))            
            ->size(5)            
            ->execute();        

        return UserResource::collection($searchResult->models());

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
