<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post(uri: '/register', action:[App\Http\Controllers\AuthController::class, 'register']);
Route::post(uri: '/login', action:[App\Http\Controllers\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::post(uri: '/logout', action:[App\Http\Controllers\AuthController::class, 'logout']);



    // blog API endpoints start here
    Route::post(uri: '/add/post', action:[App\Http\Controllers\PostController::class, 'create']);
});