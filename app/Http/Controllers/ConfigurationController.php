<?php

namespace App\Http\Controllers;

use App\Models\PrinterCategory;
use App\Object\Result;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Native\Desktop\Facades\Notification;

class ConfigurationController extends Controller
{
    //


    function saveConfiguration(Request $request) : JsonResponse {
        $result = [];

        if(!request("password")){
            $result = [
                "status" => "error",
                "message" => "Ingrese correctamente la contraseña"
            ];
            return response()->json($result, 200);
        }

        $password = "P1zZ4R4_uL@*sys-".$request->store_id;

        if($password != $request->password){
            $result = [
                "status" => "error",
                "message" => "Comuniquese con el área de soporte para poder realizar la configuración correcta"
            ];
            return response()->json($result, 200);
        }

        $token_user = $request->store_id;
        $nombreCookie = "Secure-PR-POS-PRINT";
        $duracion = 24 * 60 * 90;
        $cookie = Cookie::make($nombreCookie, $token_user, $duracion);
        
        $result = [
            "status" => "correct",
            "url" => route("view.home"),
        ];

        return response()->json($result, 200)->withCookie($cookie);

    }

       function getApplicationOn() {
            Notification::title('Aplicativo encendido!')
            ->message('Revisamos que el aplicativo este encendido, regresa al punto de venta a continuar con los pasos.')
            ->show();
            $url_p = "https://pos.app.pizzaraul.com/skt/websocket/event/printer";
            $printer_se = [
                "status" => "correct",
                "message" => "Aplicativo detectado correctamente",
                "action" => "ntf_app",
                "store_id" => $_COOKIE["Secure-PR-POS-PRINT"],
            ];

            $http_p = Http::withoutVerifying()->post($url_p, $printer_se);

            if($http_p->successful() == false){
                $message_impresion = "No se pudo procesar la solicitud correctamente";
            }else{
                $message_impresion = "Se envio datos de impresoras al punto de venta.";
            }

            $result = [
                "status" => "correct",
                "message" => $message_impresion
            ];
            return response()->json($result, 200);
        }


}
