<?php

use App\Http\Controllers\Api\Auth\RegisteredUserController;
use App\Http\Controllers\Api\V1\BlogController as V1BlogController;
use App\Http\Controllers\Api\V1\CategoryController as V1CategoryController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



//use POLICY MOTHERFUCKER, SO COMPLICATED ROUTES I HATE YOU 


// Define middleware for unauthenticated routes
Route::group(["prefix" => "V1", "namespace" => "App\Http\Controllers\Api\V1"], function () {
    Route::get('/users/{user}/subscribers', [SubscriptionController::class, 'subscribers']);
    Route::get('/blogs/{blog}/comments', [CommentController::class, 'index']);
    Route::get('/blogs/{blog}/comment-count', [CommentController::class, 'count']);
    Route::apiResource('blogs', V1BlogController::class)->only(['index', 'show']);
    Route::apiResource('categories', V1CategoryController::class)->only(['index', 'show']);

});


//post blog//post comments

// Authenticated routes with auth:sanctum middleware
Route::middleware(['auth:sanctum'])->prefix('V1')->namespace('App\Http\Controllers\Api\V1')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::post('/blogs/{blog}/comment', [CommentController::class, 'store']);
    Route::post('/users/{user}/subscribe', [SubscriptionController::class, 'toogleSubscribe']);
    Route::apiResource('blogs', V1BlogController::class)->only([ 'store']);
    Route::apiResource('categories', V1CategoryController::class)->only(['store']);
});



//delete, update

// Authenticated routes with auth:sanctum and owner middleware
Route::middleware(['auth:sanctum',"isOwner"])->prefix('V1')->namespace('App\Http\Controllers\Api\V1')->group(function () {
    Route::delete('/blogs/{blog}/comment', [CommentController::class, 'delete']);
    Route::apiResource('blogs', V1BlogController::class)->only(['destroy', 'update']);
});


// Authenticated routes with auth:sanctum and isAdmin middleware
Route::middleware(['auth:sanctum', 'isAdmin'])->prefix('V1')->namespace('App\Http\Controllers\Api\V1')->group(function () {
    Route::get('/users', [UserController::class, "index"]);
    Route::post('/users/make_admin', [RegisteredUserController::class, "makeAdmin"]);
    Route::patch('/users/{user}/update', [RegisteredUserController::class, "updateUserToAdmin"]);
    Route::apiResource('blogs', V1BlogController::class)->only(['destroy', 'update', 'store']);
    Route::apiResource('categories', V1CategoryController::class)->only(['destroy', 'update', 'store']);
});


require_once __DIR__.'/auth.php';


