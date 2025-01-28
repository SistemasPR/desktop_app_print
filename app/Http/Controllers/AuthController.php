<?php

namespace App\Http\Controllers;

use App\Models\StoreLogin;
use Exception;
use App\Object\Result;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    //
    function login(Request $request) : JsonResponse {
        $result = new Result;
        if(!request('_token')){
            $result->statusCode = Response::HTTP_BAD_REQUEST;
            $result->result = [
                'message'  => "No esta enviando un parametro requerido"
            ];
            return response()->json($result,$result->statusCode);

        }

        $password = $request->store_password;
        $store_id = $request->store_id;
        $company_id = $request->company_id;
        $send_pos = [
            "store_id" => $store_id,
            "password" => $password
        ];
        try {
            //code...
            $url = 'https://pos.pizzaraul.com/api/app/login/loginPrintApp';
            $response = Http::withoutVerifying()->post($url, $send_pos);
            if($response->successful()){
                $data = $response->json();
                $data = $data["result"];
                if($data["status"]){
                    $result->result = [
                        'message' => $data["message"],
                        'error' => false,
                        'url' => route('print.home'),
                    ];
                }else{
                    $result->result = [
                        'message' => $data["message"],
                        'error' => true,
                        'url' => '',
                    ];
                }

                
                $duracion = 360*24*60;

                $store_id_cookie_title = "store_id";
                $store_id_cookie_value = $data["store"]["id"];
                $store_title_cookie_value = $data["store"]["title"];

            
                // Crea la cookie utilizando la clase Cookie
                $cookie = Cookie::make($store_id_cookie_title, $store_id_cookie_value, $duracion);

                $store_sqlite = new StoreLogin();
                $store_sqlite->store_name = $store_title_cookie_value;
                $store_sqlite->store_id = $store_id_cookie_value;
                $store_sqlite->company_id = $company_id;
                //$store_sqlite->cookie_encrypt = $cookie;
                $store_sqlite->save();

                $result->statusCode = Response::HTTP_ACCEPTED;
                return response()->json($result,$result->statusCode)->withCookie($cookie);

            }else{
                throw new Exception("Error en respuesta con el punto de venta de Pizza Raul, contactarse con SOPORTE", 1);
            }
        } catch (\Throwable $th) {
            //throw $th;
            $result->statusCode = Response::HTTP_BAD_REQUEST;
            $result->result = [
                'message'  => $th->getMessage()
            ];
        }

        return response()->json($result,$result->statusCode);

    }
}
