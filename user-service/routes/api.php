<?php

// NOTE It is note versioning type of api endpoint. I am testing simple use case.

use Illuminate\Support\Facades\Route;

Route::post('/register', [\App\Http\Controllers\UserController::class, 'register']);
