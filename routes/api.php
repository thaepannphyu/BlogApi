<?php


use App\Http\Controllers\Api\V1\BlogController as V1BlogController;
use App\Http\Controllers\Api\V1\CategoryController as V1CategoryController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\SubscriptionController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(["prefix" => "V1", "namespace" => "App\Http\Controllers\Api\V1"],function () {
    Route::get('/users/{user}/subscribers', [SubscriptionController::class, 'subscribers']);
    Route::get('/blogs/{blog}/comments', [CommentController::class, 'index']);
    Route::middleware("auth:sanctum")->group(function(){
        Route::post('/blogs/{blog}/comment', [CommentController::class, 'store']);
        Route::delete('/blogs/{blog}/comment', [CommentController::class, 'delete']);
        Route::post('/users/{user}/subscribe', [SubscriptionController::class, 'toogleSubscribe']);
    });
});




Route::group(["prefix" => "V1", "namespace" => "App\Http\Controllers\Api\V1"], function () {
    Route::apiResource("blogs", V1BlogController::class);
    Route::apiResource("categories", V1CategoryController::class);
});


require_once __DIR__.'/auth.php';


