<?php

use App\Http\Controllers\Api\ApiPostController;
use App\Http\Controllers\Api\ApiFriendController;
use App\Http\Controllers\Api\ApiMessageController;
use App\Http\Controllers\Api\ApiGroupController;
use App\Http\Controllers\Api\ApiNotificationController;
use App\Http\Controllers\Api\ApiSearchController;
use App\Http\Controllers\Api\ApiAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [ApiAuthController::class, 'logout']);

    Route::get('/posts', [ApiPostController::class, 'index']);
    Route::post('/posts', [ApiPostController::class, 'store']);
    Route::post('/posts/{post}/comments', [ApiPostController::class, 'comment']);
    Route::post('/posts/{post}/likes', [ApiPostController::class, 'like']);
    Route::delete('/posts/{post}/likes', [ApiPostController::class, 'unlike']);

    Route::get('/friends', [ApiFriendController::class, 'index']);
    Route::get('/friends/requests', [ApiFriendController::class, 'requests']);
    Route::post('/friends', [ApiFriendController::class, 'store']);
    Route::put('/friends/{friend}/accept', [ApiFriendController::class, 'accept']);

    Route::get('/messages', [ApiMessageController::class, 'index']);
    Route::post('/messages', [ApiMessageController::class, 'store']);

    Route::get('/groups', [ApiGroupController::class, 'index']);
    Route::post('/groups', [ApiGroupController::class, 'store']);
    Route::get('/groups/{group}/posts', [ApiGroupController::class, 'posts']);
    Route::post('/groups/{group}/posts', [ApiGroupController::class, 'storePost']);
    Route::post('/groups/{group}/join', [ApiGroupController::class, 'join']);
    Route::post('/groups/{group}/leave', [ApiGroupController::class, 'leave']);

    // Route::get('/notifications', [ApiNotificationController::class, 'index']);
    // Route::post('/notifications/{notification}/read', [ApiNotificationController::class, 'markAsRead']);
});

Route::get('/users/search', [ApiSearchController::class, 'search']);
