<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class VerifyEmailController extends Controller
{   

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */    
    public function verify(Request $request)
    {                                        

        try {
            if (Crypt::decrypt($request->route('hash')) != $request->user()->getKey()) {
                abort(400, 'The link is wrong');
            }
        } catch (DecryptException $e) {
            abort(400, 'The link is wrong'); 
        }        
        
        if ($request->user()->hasVerifiedEmail()) {                        
            return response()->json(['message'=>'user already verified']);
        }        

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }
        
        return response()->json(['message'=>'verified']);
                       
    }
}
