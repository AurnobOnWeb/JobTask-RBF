<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PostsController;
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
    Route::post('/posts', [PostsController::class, 'storePosts']);
    Route::post('/likes', [PostsController::class, 'likes']);
    Route::post('/comment', [PostsController::class, 'comment']);
    Route::put('/update-comment/{id}', [PostsController::class, 'updateComment']);
    Route::delete('/delete-comment/{id}', [PostsController::class, 'deleteComment']);
    Route::get('/get-post', [PostsController::class, 'getPostData']);

});