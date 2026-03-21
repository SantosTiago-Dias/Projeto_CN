<?php

use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'login']);


Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/logout',   [LoginController::class, 'logout']);
});

Route::get('/health',function(){
    return response()->json([
        'Resposta backend'
    ]);
});
