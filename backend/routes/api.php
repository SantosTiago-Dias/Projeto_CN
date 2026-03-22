<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\isAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);


Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('/me', [AuthController::class, 'getAuthUser']);
    Route::post('/logout',   [AuthController::class, 'logout']);
    Route::apiResource('/users', UserController::class)->middleware(IsAdmin::class);
});

Route::get('/health',function(){
    return response()->json([
        'Resposta backend'
    ]);
});
