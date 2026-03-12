<?php

use Illuminate\Support\Facades\Route;

Route::get('/api/health', function () {
    return response()->json('Resposta backend',200);
});
