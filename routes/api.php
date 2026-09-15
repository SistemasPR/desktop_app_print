<?php

use App\Http\Controllers\ConfigurationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrintController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(PrintController::class)->group(function (){
    Route::post('/pruebita','index');
    Route::get('/pruebitaQr','pruebaQr');
    Route::post('/testingPrinterConnection','testingPrinterConnection');
    Route::post('/ticketBoletadeVentaApi','ticketBoletadeVentaApi');
    Route::post('/ticketComandaApi','ticketComandaApi');
    Route::post('/ticketVentaSalon','ticketVentaSalon');
    Route::post('/ticketCierreApi','ticketCierreApi');
    Route::post('/ticketPaloteoApi','ticketPaloteoApi');
    Route::post('/ticketInventarioApi','ticketInventarioApi');
    Route::post('/ticketMovimientoApi','ticketMovimientoApi');

    Route::post('/ticketBoletadeVentaApiV2','ticketBoletadeVentaApiV2');
    Route::post('/ticketComandaApiV2','ticketComandaApiV2');
    
    Route::post('/ticketCierreApiV2','ticketCierreApiV2');
    Route::post('/ticketPaloteoApiV2','ticketPaloteoApiV2');
    
    Route::post('/ticketInventarioApiV2','ticketInventarioApiV2');
    Route::post('/ticketMovimientoApiV2','ticketMovimientoApiV2');
    Route::post('/ticketTestingApiV2','ticketTestingApiV2');

    
});


Route::controller(ConfigurationController::class)->group(function (){
    Route::post('/save-configuration','saveConfiguration');
    Route::get('/getApplicationOn','getApplicationOn');
});