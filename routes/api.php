<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Models\User;
use \App\Http\Controllers\TweetsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Resources\UserResource;

Route::middleware(['auth:sanctum','verified'])->get('/user', function (Request $request ) {
    return new UserResource($request->user());
});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/tweets', [TweetsController::class,'index']);
    Route::post('/tweets', [TweetsController::class, 'store']);
    Route::get('/verify-email/{hash}', [VerifyEmailController::class, 'verify'])
        ->middleware(['throttle:6,1'])
        ->name('verification.verify'); 
    Route::post('/tweets/search', [TweetsController::class, 'search']);              

});