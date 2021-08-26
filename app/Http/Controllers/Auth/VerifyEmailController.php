<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

/**
 * @group Verification
 *
 * Api endpoints for verifying users
 */
class VerifyEmailController extends Controller
{   

    /**
     * Mark the authenticated user's email address as verified.
     * 
     * @urlParam hash string required The hash for verification . Example: GumLEeOCoLo4XNvtqVecoOi38OuGXOPfRcD5q6WF2YQ     
     * 
     * @response 200 {
     *     "message": "verified"
     * }
     * 
     * @response status=400 scenario="Invalid link" {
     *    "message": "The link is wrong"
     * }
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\Response
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
