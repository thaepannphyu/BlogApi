<?php

use App\Http\Controllers\Api\Auth\LoginUserController;
use App\Http\Controllers\Api\Auth\PasswordController;
use App\Http\Controllers\Api\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "V1"],function () {
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::post('login', [LoginUserController::class, 'login']);
    Route::middleware("auth:sanctum")->group(function(){
        Route::get('/dashboard', [RegisteredUserController::class, 'dashboard'])->middleware("isAdmin");
        Route::post('/change-password', [PasswordController::class,"changePassword" ]);
        Route::post('/logout', [LoginUserController::class,'logout']);
    });
});

?>
