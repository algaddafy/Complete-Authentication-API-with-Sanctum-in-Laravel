<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post('/users',[UserController::class,'register']);
Route::get('/login',[UserController::class,'login']);
// Route::middleware('auth:sanctum')->get('/login',[UserController::class,'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/logout',[UserController::class,'logout']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
