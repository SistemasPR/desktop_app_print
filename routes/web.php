<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\CoockieMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('view.login')
});


//auth
Route::get('/auth',function () {
    return view('auth.login');
})->name('view.login')->withoutMiddleware([CoockieMiddleware::class]);;


Route::controller(AuthController::class)->group(function (){
    Route::post('/print/app/login','login')->name('auth.login'); 
});

Route::controller(HomeController::class)->group(function (){
    Route::get('/print/home','home')->name('print.home')->middleware(CoockieMiddleware::class);
});
