<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiPostController;
use App\Http\Controllers\Api\ApiFriendController;
use App\Http\Controllers\Api\ApiMessageController;
use App\Http\Controllers\Api\ApiGroupController;
use App\Http\Controllers\Api\ApiSearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);
Route::get('/users/search', [ApiSearchController::class, 'search']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());
    Route::post('/logout', [ApiAuthController::class, 'logout']);

    // Posts
    Route::get('/posts', [ApiPostController::class, 'index']);
    Route::post('/posts', [ApiPostController::class, 'store']);
    Route::post('/posts/{post}/comments', [ApiPostController::class, 'comment']);
    Route::post('/posts/{post}/likes', [ApiPostController::class, 'like']);
    Route::delete('/posts/{post}/likes', [ApiPostController::class, 'unlike']);

    // Friends
    Route::get('/friends', [ApiFriendController::class, 'index']);
    Route::get('/friends/requests', [ApiFriendController::class, 'requests']);
    Route::post('/friends', [ApiFriendController::class, 'store']);
    Route::put('/friends/{friend}/accept', [ApiFriendController::class, 'accept']);

    // Messages
    Route::get('/messages', [ApiMessageController::class, 'index']);
    Route::post('/messages', [ApiMessageController::class, 'store']);

    // Groups
    Route::get('/groups', [ApiGroupController::class, 'index']);
    Route::post('/groups', [ApiGroupController::class, 'store']);
    Route::get('/groups/{group}/posts', [ApiGroupController::class, 'posts']);
    Route::post('/groups/{group}/posts', [ApiGroupController::class, 'storePost']);
    Route::post('/groups/{group}/join', [ApiGroupController::class, 'join']);
    Route::post('/groups/{group}/leave', [ApiGroupController::class, 'leave']);
});
