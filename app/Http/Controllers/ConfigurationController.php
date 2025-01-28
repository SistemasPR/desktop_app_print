<?php

namespace App\Http\Controllers;

use App\Models\PrinterCategory;
use App\Object\Result;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class ConfigurationController extends Controller
{
    //
    function saveConfiguration(Request $request) : JsonResponse {
        $result = new Result;
        try {
            //code...
            $printerCategory = new PrinterCategory();
            $printerCategory->printer_ip = $request->printer_ip;
            $printerCategory->printer_name = $request->printer_name;
            $printerCategory->printer_id = $request->printer_id;
            $printerCategory->category_id = $request->category_id;
            $printerCategory->all_categories = $request->all_categories;
            $printerCategory->save();
            $result->statusCode = Response::HTTP_ACCEPTED;  
        } catch (\Throwable $th) {
            //throw $th;
        }
        return response()->json($result, 200);
    }
}
