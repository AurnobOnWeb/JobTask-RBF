<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Post\CommentsController;
use App\Http\Controllers\Post\LikesController;
use App\Http\Controllers\Post\PostsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



//public Routes
Route::post('/login', [AuthController::class, 'login']);


//protected Routes
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/get-post', [PostsController::class, 'getPostData']);
    Route::post('/posts', [PostsController::class, 'storePosts']);
    Route::post('/likes', [LikesController::class, 'likes']);
    Route::post('/comment', [CommentsController::class, 'comment']);
    Route::put('/update-comment/{id}', [CommentsController::class, 'updateComment']);
    Route::delete('/delete-comment/{id}', [CommentsController::class, 'deleteComment']);
});