<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupPostController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'apiRegister']);
Route::post('/login', [AuthController::class, 'apiLogin']);
Route::post('/logout', [AuthController::class, 'apiLogout']);

Route::middleware('auth:api')->group(function () {
    Route::get('/posts', [PostController::class, 'apiIndex']);
    Route::post('/posts', [PostController::class, 'apiStore']);
    Route::put('/posts/{post}', [PostController::class, 'apiUpdate']);
    Route::delete('/posts/{post}', [PostController::class, 'apiDestroy']);

    Route::post('/posts/{post}/comments', [CommentController::class, 'apiStore']);

    Route::post('/posts/{post}/likes', [LikeController::class, 'apiStore']);
    Route::delete('/posts/{post}/likes', [LikeController::class, 'apiDestroy']);

    Route::get('/friends', [FriendController::class, 'apiIndex']);
    Route::get('/friends/requests', [FriendController::class, 'apiRequests']);
    Route::post('/friends', [FriendController::class, 'apiStore']);
    Route::put('/friends/{friend}/accept', [FriendController::class, 'apiAccept']);

    Route::get('/messages', [MessageController::class, 'apiIndex']);
    Route::post('/messages', [MessageController::class, 'apiStore']);

    Route::get('/groups', [GroupController::class, 'apiIndex']);
    Route::post('/groups', [GroupController::class, 'apiStore']);
    Route::post('/groups/{group}/join', [GroupController::class, 'apiJoin']);
    Route::post('/groups/{group}/leave', [GroupController::class, 'apiLeave']);

    Route::get('/groups/{group}/posts', [GroupPostController::class, 'apiIndex']);
    Route::post('/groups/{group}/posts', [GroupPostController::class, 'apiStore']);
});
