<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\CoockieMiddleware;
use Illuminate\Support\Facades\Route;




//auth
Route::get('/printer-app',function () {
    return view('home_v2');
})->name('view.home2')->withoutMiddleware([CoockieMiddleware::class]);

// Route::get('/',function () {
//     return view('configuration');
// })->name('view.configuration');


Route::controller(AuthController::class)->group(function (){
    Route::post('/print/app/login','login')->name('auth.login'); 
});

Route::controller(HomeController::class)->group(function (){
    Route::get('/','configuration')->name('view.configuration');
    Route::get('/print/home','home')->name('view.home')->middleware(CoockieMiddleware::class);
});
