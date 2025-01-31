<?php

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
});