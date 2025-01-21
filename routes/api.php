<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//hello world
Route::get('/hello', function () {
    return response()->json(['message' => 'Hello World!']);
});