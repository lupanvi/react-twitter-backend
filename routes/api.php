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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {

	//Route::get('/posts/{post}','PostsController@show');
	Route::get('/tweets',[TweetsController::class,'index']);
	Route::post('/tweets',[TweetsController::class, 'store']);

	/*Route::patch('/posts/{post}','PostsController@update');
	Route::post('/posts','PostsController@store');
	Route::delete('/posts/{post}','PostsController@destroy');
	Route::post('/posts/{post}/likes','LikesController@store');
	Route::post('/posts/{post}/dislike','LikesController@destroy');
	Route::post('/posts/{post}/comments','CommentsController@store');
	Route::get('/posts/{post}/comments/all','CommentsController@index');*/

});