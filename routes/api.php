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
use App\Http\Controllers\UserController;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user', [UserController::class, 'user'])->middleware(['verified']);
    Route::get('/tweets', [TweetsController::class,'index']);
    Route::post('/tweets', [TweetsController::class, 'store']);
    Route::get('/verify-email/{hash}', [VerifyEmailController::class, 'verify'])
        ->middleware(['throttle:6,1'])
        ->name('verification.verify'); 
    Route::post('/tweets/search', [TweetsController::class, 'search']);
    Route::post('/tweets/search_with_prefix', [TweetsController::class, 'searchWithPrefix']);

});