<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;

/**
 * @group Auth endpoints 
 */
class UserController extends Controller
{
    /**
     * Shows authenticated user information
     * 
     * @response 200 {
     *  "id": 1,
     *  "name": "Demo",
     *  "verified": true,
     *  "username": "demo",
     *  "avatar_path": "default_avatar.png"
     *  }
     * 
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *     
     * @return \Illuminate\Http\Response
     */  
    public function user()
    {
        return response()->json(new UserResource(auth()->user()));
    }
}
