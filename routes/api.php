<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Dashboard\Auth\AuthController;
use App\Http\Controllers\Api\V1\Dashboard\User\UserController;
use App\Http\Controllers\Api\V1\Dashboard\User\UserProfileController;
use App\Http\Controllers\Api\V1\Dashboard\User\ChangePasswordController;
use App\Http\Controllers\Api\V1\ForgotPassword\ForgotPasswordController;

//register , login , forgetPassword , changePassword , logout
Route::prefix('v1')->group(function(){
    Route::controller(AuthController::class)->prefix('auth')->group(function () {
        Route::post('/login','login');
        Route::post('/logout','logout');
        Route::post('/register', 'register');
    });
    Route::controller(ForgotPasswordController::class)->prefix("/forgotPassword")->group(function(){
        Route::post("sendCode","sendCodeEmail");
        Route::post('verifyCode','verifyCodeEmail');
        Route::post('resendCode','resendCode');
        Route::post('newPassword','newPassword');
    });
});
Route::prefix('v1/admin')->group(function(){
    Route::post('users/changeStatus',[UserController::class ,'changeStatus']);
    Route::apiResource('users', UserController::class);
    Route::apiSingleton('profile', UserProfileController::class);
    Route::put('profile/change-password', ChangePasswordController::class);
});
Route::prefix('v1/profile')->group(function(){

});

