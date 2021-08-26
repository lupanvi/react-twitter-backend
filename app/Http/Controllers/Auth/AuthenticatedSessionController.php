<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @group Authentication
 *
 * Endpoints for managing authentication
 */
class AuthenticatedSessionController extends Controller
{   
    /**
     * Handle an incoming authentication request.
     * 
     * @bodyParam email string required The email of the user. Example: demo@demo.com
     * @bodyParam password string required The password of the user. Example: password
     * 
     * @response {
     *  "id": "1",
     *  "name": "Test",
     *  "verified": true,
     *  "username":"test",
     *  "avatar_path":"http://localhost:8000/images/default_avatar.png"
     * }
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoginRequest $request)
    {                

        $request->authenticate();                
        
        $request->session()->regenerate();
        
        return response()->json(new UserResource($request->user()));        
        
    }

    /**
     * Destroy an authenticated session.
     * 
     * @response {
     *  "message": "logged out successfully"
     * }
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();        

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        
        return response()->json(['message'=>'logged out successfully']);
        
    }
}
