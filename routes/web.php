<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


//auth
Route::get('/auth',function () {
    return view('auth.login');
});