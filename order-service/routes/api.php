<?php

use Illuminate\Support\Facades\Route;

Route::post('/orders', [\App\Http\Controllers\OrderController::class, 'store']);
