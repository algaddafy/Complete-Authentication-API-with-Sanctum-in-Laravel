<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post('/users',[UserController::class,'register']);
Route::get('/login',[UserController::class,'login']);
// Route::middleware('auth:sanctum')->get('/login',[UserController::class,'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/logout',[UserController::class,'logout']);
    Route::get('/logged_user',[UserController::class,'logged_user']);
    Route::post('/change_password',[UserController::class,'change_password_with_old']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
