<?php

use App\Http\Controllers\googleAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::post('/auth/google', [googleAuthController::class, 'login']);
