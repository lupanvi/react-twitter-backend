<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * @group Registration
 *
 * Endpoint for registering users
 */
class RegisteredUserController extends Controller
{    

    /**
     * Handle an incoming registration request.
     * 
     * @bodyParam name string required The name of the user. Example: demo@demo.com
     * @bodyParam email string required The email of the user. Example: demo@demo.com
     * @bodyParam username string required The username of the user. Example: demo
     * @bodyParam password string required The password of the user. Example: password
     * @bodyParam password string required The password confirmation of the user. Example: password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        Auth::login($user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]));

        event(new Registered($user));
        
        return response()->json(['message'=>'registered'], Response::HTTP_CREATED);
        
    }
}
