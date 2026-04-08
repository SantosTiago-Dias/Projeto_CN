<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\isAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::post('/upload',[TaskController::class, 'upload']);

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('/weather', [App\Http\Controllers\WeatherController::class, 'getCurrentWeather']);
    Route::get('/me', [AuthController::class, 'getAuthUser']);
    Route::post('/logout',   [AuthController::class, 'logout']);

    Route::get('/users/workers', [UserController::class, 'getWorkers'])->middleware(IsAdmin::class);
    Route::apiResource('/users', UserController::class)->middleware(IsAdmin::class);

    Route::get('/mytasks', [TaskController::class, 'getTaskUser']);
    Route::put('/tasks/{task}/changestatus', [TaskController::class, 'changeStatus']);
    Route::apiResource('/tasks', TaskController::class)->middleware(IsAdmin::class);

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{notification}', [NotificationController::class, 'markAsRead']);
    Broadcast::routes(['middleware' => ['auth:sanctum']]);

});

Route::get('/health',function(){
    return response()->json([
        'Resposta backend'
    ]);
});
