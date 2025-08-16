<?php

use App\Models\Area\Area;
use Illuminate\Http\Request;
use App\Models\Medicine\Medicine;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Dashboard\Auth\AuthController;
use App\Http\Controllers\Api\V1\Dashboard\User\UserController;
use App\Http\Controllers\API\V1\Dashboard\Areas\AreaController;
use App\Http\Controllers\Api\V1\Dashboard\Branch\BranchController;
use App\Http\Controllers\Api\V1\Dashboard\User\UserProfileController;
use App\Http\Controllers\Api\V1\Dashboard\Category\CategoryController;
use App\Http\Controllers\Api\V1\Dashboard\Medicine\MedicineController;
use App\Http\Controllers\Api\V1\Dashboard\User\ChangePasswordController;
use App\Http\Controllers\Api\V1\ForgotPassword\ForgotPasswordController;
use App\Http\Controllers\Api\V1\Dashboard\ProductMedia\ProductMediaController;

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
    Route::apiResource('medicineMedia' , ProductMediaController::class);
    Route::apiSingleton('profile', UserProfileController::class);
    Route::controller(CategoryController::class)->group(function(){
        Route::get("categories","index");
        Route::get('categories/{categoryId}','show');
        Route::post('categories','store');
        Route::put('categories/{categoryId}','update');
        Route::delete('categories/{categoryId}','destroy');
    });
    Route::controller(BranchController::class)->group(function(){
        Route::get("branches","index");
        Route::get('branches/{branchId}','show');
        Route::post('branches','store');
        Route::put('branches/{branchId}','update');
        Route::delete('branches/{branchId}','destroy');
    });
     Route::controller(MedicineController::class)->group(function(){
        Route::get("medicines","index");
        Route::get('medicines/{medicineId}','show');
        Route::post('medicines','store');
        Route::put('medicines/{medicineId}','update');
        Route::delete('medicines/{medicineId}','destroy');
    });
    Route::controller(AreaController::class)->group(function(){
        Route::get("areas","index");
        Route::get('areas/{id}','show');
        Route::post('areas','store');
        Route::put('areas/{id}','update');
        Route::delete('areas/{id}','destroy');
    });
    Route::put('profile/change-password', ChangePasswordController::class);

});
Route::prefix('v1/profile')->group(function(){

});

