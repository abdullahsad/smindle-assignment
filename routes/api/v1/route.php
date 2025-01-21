<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\OrderController;

//to store orders in the database
Route::post('/orders', [OrderController::class, 'store']);