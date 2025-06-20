<?php

namespace App\Http\Controllers;

use Mike42\Escpos\Printer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Carbon\Carbon;
date_default_timezone_set('America/Lima');

class PrintController extends Controller
{
    //
    //PRUEBA
    public function index(Request $request) {
        self::ticketCocina($request->order,$request->items,$request->printer);
        return response()->json(["message" => "HOLAAAAAAA"], 200);
    }

    public function ticketBoletadeVentaApi(Request $request) {
    
        $order = (object) $request->order;
        $printers = (object) $request->printer;
        $printer_ip = "";
        $printer_name = ""; 
        $contain_category = true;
        foreach ($printers as $key ) {
            $key = (object)$key;
            info(json_encode(["printers_key" => $key]));
            # code...
            if($key->printer_status == 2 || $key->printer_status == 3){
                if($key->printer_ip != null){
                    $printer_ip = $key->printer_ip;
                }else{
                    $printer_name = $key->printer_title;
                }
            }
        }

        $arPrinterPrincipal = [
            "printer_ip"=>$printer_ip,
            "printer_name"=>$printer_name
        ];

        self::ticketBoletadeVenta($request->order,$request->items,$request->store,$request->correlativo,$arPrinterPrincipal,"copia");

        $arApp = ["ANDROID",'IOS','WEB','CALL'];
        $source_app = strtoupper($order->source_app);
        if(in_array($source_app,$arApp)){
            //self::ticketBoletadeVenta($request->order,$request->items,$request->store,$request->correlativo,$request->printer);
            self::ticketDeliveryDriver($request->order,$request->items,$request->store,$arPrinterPrincipal);
        }
        return response()->json(["message" => "se imprimio correctamente"], 200);
    }


    public function ticketVentaSalon(Request $request) {
        
        $order = (object) $request->order;
        $items = (object) $request->items;
        $printers = (object) $request->printer;

        if($printers == []){
            return response()->json(["message" => "Comunicarse con sistemas para verificar las impresoras"], 404);
        }

        info(json_encode(["order"=>$order,"items"=>$items,"printers"=>$printers]));

        //Desglose de categorias
        $arItemsByCategory = [];
        foreach ($items as $key) {
            # code...
            $key = (object) $key;
            // Inicializa la categoría si no existe
            if (!isset($arItemsByCategory[$key->title])) {
                $arItemsByCategory[$key->title] = [];
            }
            // Item ingresado para la categoria
            $arItemsByCategory[$key->title][] = $key;
        }

        info(json_encode(["arItemsByCategory" => $arItemsByCategory]));

        $printer_ip = "";
        $printer_name = ""; 
        $contain_category = true;
        $printer_active = "";
        foreach ($printers as $key ) {
            $key = (object)$key;
            info(json_encode(["printers_key" => $key]));
            # code...
            if($key->printer_status == 2 || $key->printer_status == 3){
                if($key->printer_ip != null){
                    $printer_ip = $key->printer_ip;
                }else{
                    $printer_name = $key->printer_title;
                }
            }
            $printer_ip_sec = "";
            $printer_name_sec = "";
            if($key->printer_ip != null){
                $printer_ip_sec = $key->printer_ip;
            }else{
                $printer_name_sec  = $key->printer_title;
            }

            $arPrinter = [
                "printer_ip"=>$printer_ip_sec,
                "printer_name"=>$printer_name_sec
            ];
            if(isset($key->category_id)){
                if($key->category_id != null && $key->category_id != ""){
                    if (isset($arItemsByCategory[$key->printer_title])) {
                        if($key->printer_title != $printer_active){
                            $printer_active = $key->printer_title;
                            self::ticketCocina($request->order,$arItemsByCategory[$key->printer_title],$arPrinter);
                        }
                    }
                }else{
                    $contain_category = false;
                }
            }else{
                $contain_category = false;
            }
        }


        $arPrinterPrincipal = [
            "printer_ip"=>$printer_ip,
            "printer_name"=>$printer_name
        ];

        if(!$contain_category){
            self::ticketCocina($request->order,$items,$arPrinterPrincipal);
        }

        if($order->paid == 1){
            self::ticketBoletadeVenta($request->order,$request->items,$request->store,$request->correlativo,$arPrinterPrincipal,"normal");
        }

        return response()->json(["message" => "se imprimio correctamente"], 200);
    }
    public function ticketComandaApi(Request $request) {
        $order = (object) $request->order;
        $items = (object) $request->items;
        $printers = (object) $request->printer;
        //Desglose de categorias
        $arItemsByCategory = [];
        $printer_active = "";
        foreach ($items as $key) {
            # code...
            $key = (object) $key;
            // Inicializa la categoría si no existe
            if (!isset($arItemsByCategory[$key->title])) {
                $arItemsByCategory[$key->title] = [];
            }
            // Item ingresado para la categoria
            $arItemsByCategory[$key->title][] = $key;
        }
        info(json_encode(["arItemsByCategory" => $arItemsByCategory]));
        $printer_ip = "";
        $printer_name = ""; 
        $contain_category = true;
        $result = [];
        foreach ($printers as $key ) {
            $key = (object)$key;
            info(json_encode(["printers_key" => $key]));
            # code...
            if($key->printer_status == 2 || $key->printer_status == 3){
                if($key->printer_ip != null){
                    $printer_ip = $key->printer_ip;
                }else{
                    $printer_name = $key->printer_title;
                }
            }
            $printer_ip_sec = "";
            $printer_name_sec = "";
            if($key->printer_ip != null){
                $printer_ip_sec = $key->printer_ip;
            }else{
                $printer_name_sec  = $key->printer_title;
            }

            $arPrinter = [
                "printer_ip"=>$printer_ip_sec,
                "printer_name"=>$printer_name_sec
            ];
            if(isset($key->category_id)){
                if($key->category_id != null && $key->category_id != ""){
                    if (isset($arItemsByCategory[$key->printer_title])) {
                        if($key->printer_title != $printer_active){
                            $printer_active = $key->printer_title;
                            $result = self::ticketCocina($request->order,$arItemsByCategory[$key->printer_title],$arPrinter);
                        }
                    }
                }else{
                    $contain_category = false;
                }
            }else{
                $contain_category = false;
            }
        }
        
        $arPrinterPrincipal = [
            "printer_ip"=>$printer_ip,
            "printer_name"=>$printer_name
        ];

        if(!$contain_category){
            $result = self::ticketCocina($request->order,$items,$arPrinterPrincipal);
        }

        
        return response()->json($result, 200);
    }

    public function ticketCierreApi(Request $request) {
        //info(json_encode($request->all()));
        
        $printers = (object) $request->printer;
        $printer_ip = "";
        $printer_name = ""; 
        foreach ($printers as $key ) {
            $key = (object)$key;
            info(json_encode(["printers_key" => $key]));
            # code...
            if($key->printer_status == 2 || $key->printer_status == 3){
                if($key->printer_ip != null){
                    $printer_ip = $key->printer_ip;
                }else{
                    $printer_name = $key->printer_title;
                }
            }
        }

        $arPrinterPrincipal = [
            "printer_ip"=>$printer_ip,
            "printer_name"=>$printer_name
        ];

        self::ticketCierreCaja($request->store,$request->apertura_s,$request->suma_S,$request->ventas,$request->transactions_S,$request->usuario,$request->store_balance,$request->mercaderia,$arPrinterPrincipal);
        return response()->json(["message" => "se imprimio correctamente"], 200);
    }

    public function ticketPaloteoApi(Request $request) {
        //info(json_encode($request->all()));
        $printers = (object) $request->printer;
        $printer_ip = "";
        $printer_name = ""; 
        foreach ($printers as $key ) {
            $key = (object)$key;
            info(json_encode(["printers_key" => $key]));
            # code...
            if($key->printer_status == 2 || $key->printer_status == 3){
                if($key->printer_ip != null){
                    $printer_ip = $key->printer_ip;
                }else{
                    $printer_name = $key->printer_title;
                }
            }
        }

        $arPrinterPrincipal = [
            "printer_ip"=>$printer_ip,
            "printer_name"=>$printer_name
        ];

        self::ticketPaloteo($request->store,$request->data,$arPrinterPrincipal);
        return response()->json(["message" => "se imprimio correctamente"], 200);
    }

    public function ticketInventarioApi(Request $request) {
        //info(json_encode($request->all()));
        $printers = (object) $request->printer;
        $printer_ip = "";
        $printer_name = ""; 
        foreach ($printers as $key ) {
            $key = (object)$key;
            info(json_encode(["printers_key" => $key]));
            # code...
            if($key->printer_status == 2 || $key->printer_status == 3){
                if($key->printer_ip != null){
                    $printer_ip = $key->printer_ip;
                }else{
                    $printer_name = $key->printer_title;
                }
            }
        }

        $arPrinterPrincipal = [
            "printer_ip"=>$printer_ip,
            "printer_name"=>$printer_name
        ];
        self::ticketInventario($request->store,$request->data,$arPrinterPrincipal);
        return response()->json(["message" => "se imprimio correctamente"], 200);
    }

    public function ticketMovimientoApi(Request $request) {
        $printers = (object) $request->printer;
        $printer_ip = "";
        $printer_name = ""; 
        foreach ($printers as $key ) {
            $key = (object)$key;
            info(json_encode(["printers_key" => $key]));
            # code...
            if($key->printer_status == 2 || $key->printer_status == 3){
                if($key->printer_ip != null){
                    $printer_ip = $key->printer_ip;
                }else{
                    $printer_name = $key->printer_title;
                }
            }
        }

        $arPrinterPrincipal = [
            "printer_ip"=>$printer_ip,
            "printer_name"=>$printer_name
        ];
        self::ticketMovimiento($request->movimiento,$request->store,$arPrinterPrincipal);
        return response()->json(["message" => "se imprimio correctamente"], 200);
    }



    public function testingPrinterConnection(Request $request) {
        try {
            //code...
            $ip = "192.168.1.101";
            $port = 9100;
            $connector = new NetworkPrintConnector($ip, $port);
            $impresora = new Printer($connector);
            $impresora->text("<<<<<<<<<<<<<<<<<<<<<<<<<<>>>>>>>>>>>>>>>>>>>>>>>>>>>>\n");   
            $impresora->text("LA CONEXIÓN SE REALIZO CON EXITO\n");   
            $impresora->text("<<<<<<<<<<<<<<<<<<<<<<<<<<>>>>>>>>>>>>>>>>>>>>>>>>>>>>\n");   
            $impresora->feed(5);
            $impresora->cut();
            $impresora->close();
            return response()->json(["message" => "CONEXIÓN EXITOSA"], 200 );
        } catch (\Throwable $th) {
            //throw $th;
            // Capturar mensaje del error
            $errorMessage = $th->getMessage();
            // Capturar el archivo donde ocurrió el error
            $errorFile = $th->getFile();
            // Capturar la línea donde ocurrió el error
            $errorLine = $th->getLine();
            $data = [
                "message" => "Error: {$errorMessage} en el archivo {$errorFile} en la línea {$errorLine}"
            ];
            Log::channel('stderr')->info("Error: {$errorMessage} en el archivo {$errorFile} en la línea {$errorLine}");
            return  $data;
        }
    }

    public static function ticketBoletadeVenta($order,$items,$store,$correlativo,$printer,$type_pri){
        
        $order = (object) $order;
        $items = (object) $items;
        $store = (object) $store;
        //$connector = new WindowsPrintConnector($nombreImpresora);
        try {


            if($printer["printer_ip"] != null){
                $ip = $printer["printer_ip"];
                $port = 9100;
                $connector = new NetworkPrintConnector($ip, $port);
            }else{
                $connector = new WindowsPrintConnector($printer["printer_name"]);
            }

            $impresora = new Printer($connector);
            if($correlativo != null || $correlativo != ""){
                $impresora->setFont(PRINTER::FONT_B);
                $arOtr = ["OTR","OTROS","Otro","DNI"];
                switch ($order->fiscal_doc_type) {
                    case 'RUC':                
                    case 'FACTURA':
                        $title_impresion = "FACTURA ELECTRÓNICA";
                        break;
                    case 'DNI':
                        $title_impresion = "BOLETA DE VENTA ELECTRÓNICA";
                        break;
                    default:
                        # code...
                        $title_impresion = "BOLETA SIMPLE";
                        break;
                }
    
                $impresora->setFont(PRINTER::FONT_B);
                $impresora->setJustification(Printer::JUSTIFY_CENTER);
                $impresora->setTextSize(1, 1);
                $impresora->setEmphasis(true);
    
                if($correlativo == null || $correlativo == ""){
                    $impresora->text("PRE CUENTA\n");
                }
    
                $impresora->text("$store->efact_prn_motto\n");
    
                $impresora->text("$store->efact_prn_title\n");   
                $impresora->text("$store->efact_prn_ruc\n");   
                $impresora->text("$store->street_name  $store->street_number\n");   
                $impresora->text("$store->district_old, LIMA - LIMA\n");   
                $impresora->text("(01) 207 - 8130\n");   
                $impresora->setFont(PRINTER::FONT_B);
                //$impresora->text("www.pizzaraul.work\n");
                $impresora->setEmphasis(true);
                $impresora->text("================================================================\n");
                $impresora->setFont(PRINTER::FONT_B);
                //contenido source
                $phone = $order->user_phone == null || $order->user_phone == "999999999" ? '' : $order->user_phone;
                $impresora->text("$title_impresion\n");
                $impresora->text("$correlativo\n");
                $impresora->setTextSize(1, 1);
                $impresora->text("================================================================\n");
                $impresora->setJustification(Printer::JUSTIFY_LEFT);
                
                $date =  date('d/m/Y', strtotime($order->created_at));
                $horaActual = date('H:i:s', strtotime($order->created_at));
                $date_print =  date('d/m/Y H:i:s');

                Log::info(json_encode(["date"=>$date,"hora" => $horaActual]));
                $fiscal_address = $order->fiscal_address == null || $order->fiscal_address == "" ? "" : $order->fiscal_address;
                $impresora->text("F.Impresión: $date_print\n");
                $impresora->text("F.Emisión: $date\n");
                $impresora->text("H.Emisión: $horaActual\n");
                $impresora->text("Orden de compra: $order->id\n");
                $impresora->text("Cliente: $order->fiscal_name\n");
                $impresora->text("Telefono: $phone\n");
                $impresora->text("$order->fiscal_doc_type: $order->fiscal_doc_number\n");
                $impresora->text("Dirección: $fiscal_address \n");
                //$impresora->text("Referencia: $order->reference \n");
            }
            $impresora->text("================================================================\n");
    
            $impresora->text("i     Descripción                                             s/\n");
            $impresora->text("----------------------------------------------------------------\n");
    
            $index = "";
            $auxItem = 0;
            $auxPromId = 0;
    
            foreach ($items as $item) {
                $item = (object) $item;
                if($correlativo != null || $correlativo != ""){
                    $impresora->setFont(PRINTER::FONT_B);
                }else{
                    $impresora->setFont(PRINTER::FONT_A);
                }
                if(($item->item_id != $auxItem ) && ($item->promotion_id != null) ){
                    //$index ++;
                    $auxItem = $item->item_id;
                    $auxPromId = $item->promotion_id;
                    $nombre = mb_substr($item->promotion_name, 0, 50);
                    $precio = number_format($item->price, 2, '.', ''); 
                    $qprom = $index."   ".$item->q_prom."x ";
                    $order_value = $precio * $item->q_prom;
                    // Divide la línea en tres partes
                    $parteIzquierda = $qprom . $nombre;
                    $parteCentro = "";
                    $parteDerecha = $order_value;
                
                    // Calcula la cantidad de espacios entre las partes
                    $espaciosCentro = self::CalculaEspacio($parteIzquierda,$parteDerecha);
                    
                    // Alineación a la izquierda
                    $impresora->text($parteIzquierda);
                    
                    // Alineación central (agrega espacios en blanco)
                    for ($i = 0; $i < $espaciosCentro; $i++) {
                        $parteCentro .= " ";
                    }
                    $impresora->text($parteCentro);
                    
                    // Alineación a la derecha
                    $impresora->text($parteDerecha);
                    $impresora->text("\n");

                    $q_promo = $item->q_prom;
                    
                    $terms = $item->product_terms != null ? ' ('.$item->product_terms.')' : '';
                    if($item->size_id == 9 || $item->size_id == 14) {
                        $arApp = ["ANDROID",'IOS','WEB'];
                        $source_app = strtoupper($order->source_app);
                        $q_total = 0;
                        if(in_array($source_app,$arApp) && $q_promo != 0){
                            $q_total = $item->quantity;
                        }else{
                            $q_total = $item->quantity;
                        }

                        $nombre_it = $q_total.'x'.' '.$item->product_name.' '.$terms;
                    }else{
                        $arApp = ["ANDROID",'IOS','WEB'];
                        $source_app = strtoupper($order->source_app);
                        $q_total = 0;
                        if(in_array($source_app,$arApp) && $q_promo != 0){
                            $q_total = $item->quantity;
                        }else{
                            $q_total = $item->quantity;
                        }
                        $arSizeName = explode('(',$item->size_name);
                        $nombre_it = $q_total.'x'.' '.$item->product_name.' '.$terms.' '.$arSizeName[0];
                    }

                    //$nombre_it = $item->quantity.'x'.' '.$item->product_name.' '.$item->size_name;
                    if($correlativo != null || $correlativo != ""){
                        $impresora->setFont(PRINTER::FONT_B);
                    }else{
                        $impresora->setFont(PRINTER::FONT_A);
                    }
                    $impresora->text("  >> $nombre_it \n");

                }elseif($item->promotion_id == $auxPromId && $item->promotion_id != null){
                    $terms = $item->product_terms != null ? ' ('.$item->product_terms.')' : '';
                    $q_promo = $item->q_prom;
                    if($item->size_id == 9 || $item->size_id == 14) {
                        $arApp = ["ANDROID",'IOS','WEB'];
                        $source_app = strtoupper($order->source_app);
                        $q_total = 0;
                        if(in_array($source_app,$arApp) && $q_promo != 0){
                            $q_total = $item->quantity;
                        }else{
                            $q_total = $item->quantity;
                        }

                        $nombre_it = $q_total.'x'.' '.$item->product_name.' '.$terms;
                    }else{
                        $arApp = ["ANDROID",'IOS','WEB'];
                        $source_app = strtoupper($order->source_app);
                        $q_total = 0;
                        if(in_array($source_app,$arApp) && $q_promo != 0){
                            $q_total = $item->quantity;
                        }else{
                            $q_total = $item->quantity;
                        }
                        $arSizeName = explode('(',$item->size_name);
                        $nombre_it = $q_total.'x'.' '.$item->product_name.' '.$terms.' '.$arSizeName[0];
                    }

                    //$nombre_it = $item->quantity.'x'.' '.$item->product_name.' '.$item->size_name;
                    if($correlativo != null || $correlativo != ""){
                        $impresora->setFont(PRINTER::FONT_B);
                    }else{
                        $impresora->setFont(PRINTER::FONT_A);
                    }
                    $impresora->text("  >> $nombre_it \n");
                }else{
                    $auxItem = 0;
                }

                if($auxItem == 0){
                    $size_name = "";

                    if($item->size_id == 9 || $item->size_id == 14) {
                        $terms = $item->product_terms != null ? ' ('.$item->product_terms.')' : '';
                        $nombre = $item->quantity.'x'.' '.$item->product_name.' '.$terms;
                    }else{
                        $terms = $item->product_terms != null ? ' ('.$item->product_terms.')' : '';
                        $arSizeName = explode('(',$item->size_name);
                        $nombre = $item->quantity.'x'.' '.$item->product_name.' '.$terms.' '.$arSizeName[0];
                    }
                    $precio = number_format($item->price, 2, '.', '');
                    $discount = $item->discount;
                    $order_value = $precio * $item->quantity - $discount;
                    if($order_value == 0 || $order_value == 0.00){
                        $order_value = "";
                    }else{
                        $order_value = number_format($order_value, 2, '.', '');
                    }
                    //$index ++;
                    // Divide la línea en tres partes
                    $parteIzquierda = "$index  " . $nombre;
                    $parteCentro = "";
                    $parteDerecha = $order_value;
                
                    // Calcula la cantidad de espacios entre las partes
                    $espaciosCentro = self::CalculaEspacio($parteIzquierda,$parteDerecha);
                    
                    // Alineación a la izquierda
                    $impresora->text($parteIzquierda);
                    
                    // Alineación central (agrega espacios en blanco)
                    for ($i = 0; $i < $espaciosCentro; $i++) {
                        $parteCentro .= " ";
                    }
                    $impresora->text($parteCentro);
                    
                    // Alineación a la derecha
                    $impresora->text($parteDerecha);
                    $impresora->text("\n");
                }
            
    
            }

            if($correlativo != null || $correlativo != ""){
                $impresora->setFont(PRINTER::FONT_B);
                $igv = $store->cfd_igv;
                $igv = number_format($igv, 2, '.', '');
                $porIgv = $igv / 100;
                $porIgv = doubleval($porIgv);
                $order_sin_impuesto = $order->total_price / ( 1 + $porIgv );
                $order_sin_impuesto =  round($order_sin_impuesto, 2);

                $msjOP = "OP. GRAVADAS: S/";
                $impresora->text($msjOP);
                $espaciosCentro = 0;
                $espaciosCentro = self::CalculaEspacio($msjOP,$order_sin_impuesto);
                $parteCentro = "";
                for ($i = 0; $i < $espaciosCentro; $i++) {
                    $parteCentro .= " ";
                }
                $impresora->text($parteCentro);
                $impresora->text("$order_sin_impuesto");
                $impresora->text("\n");


                
                $msjIgv = "IGV ($igv%): S/";
                $impresora->text($msjIgv);
                $order_impuesto = $order_sin_impuesto * $porIgv;
                $order_impuesto = round($order_impuesto, 2);
                $espaciosCentro = 0;
                $espaciosCentro = self::CalculaEspacio($msjIgv,$order_impuesto);
                $parteCentro = "";
                for ($i = 0; $i < $espaciosCentro; $i++) {
                    $parteCentro .= " ";
                }
                $impresora->text($parteCentro);
                $impresora->text("$order_impuesto");
                $impresora->text("\n");
        
                $msjTotalPagar = "TOTAL A PAGAR S/";
                $impresora->text($msjTotalPagar);
                $totalPagar = number_format($order->total_price, 2, '.', '');
                $espaciosCentro = 0;
                $espaciosCentro = self::CalculaEspacio($msjTotalPagar,$totalPagar);
                $parteCentro = "";
                for ($i = 0; $i < $espaciosCentro; $i++) {
                    $parteCentro .= " ";
                }
                $impresora->text($parteCentro);
                $impresora->text("$totalPagar");
                $impresora->text("\n");
                
                $arApp = ["ANDROID",'IOS','WEB'];
                $source_app_validate = strtoupper($order->source_app);
                $source_app = "";
                if(in_array($source_app_validate,$arApp)){
                    $source_app = "APLICATIVO";
                }elseif($source_app_validate == "CALL"){
                    $source_app = "CALLCENTER";
                }else{
                    $source_app = "TIENDA";
                }
        
                $impresora->setFont(PRINTER::FONT_B);
                $impresora->text("================================================================\n");;
                $impresora->text("Información Adicional\n");
                $impresora->text("N° de pedido de tienda: $order->store_order_id \n"); //

                $forma_pago = "";
                $payment_method = strtoupper($order->payment_method);
                $paid = ""; //$order->paid == 1 ? "Pagado" : "Pendiente";
                if($payment_method == "CASH"){
                    if($order->payment_received != null && $order->payment_received != ""){
                        if($order->payment_received == "Pago exacto" || $order->payment_received == "Pagará exacto"){
                            $forma_pago = "SOLES $totalPagar";
                        }else{
                            $vuelto = floatval($order->payment_received) - floatval($order->total_price);
                            $vuelto = number_format($vuelto, 2, '.', '');
                            $forma_pago = "SOLES $order->payment_received VUELTO:$vuelto";
                        }
                    }else{
                        $forma_pago = "SOLES $totalPagar";
                    }
                }elseif($payment_method == "CARD"){
                    if($order->payment_mp != null && $order->payment_mp != ""){
                        $forma_pago = "Tarjeta - $order->payment_mp - $totalPagar";
                    }else{
                        $forma_pago = "Tarjeta - $totalPagar";
                    }
                }elseif($payment_method == "YAPE"){
                        $forma_pago = "YAPE - s/$totalPagar";
                }elseif($payment_method == "PYA"){
                    $forma_pago = "PEDIDOSYA! - s/$totalPagar";
                }else{
                    $change =  ($order->payment_with_cash + $order->payment_with_card) - $order->total_price;
                    $forma_pago = "MIXTO - E: s/$order->payment_with_cash - T: s/$order->payment_with_card ($order->payment_mp) - V: s/$change";
                }

                $mesa = $order->user_table == null ? "" : " -- " . $order->user_table;
                $impresora->text("\nMesa:".$mesa);
                $impresora->text("Forma de Pago: ".' '."$forma_pago"."\n");
                $impresora->text("$paid\n");
                $impresora->text("Caja: 01\n");
                $impresora->text("Canal: $source_app\n");
                $impresora->text("Cliente: $order->user_name\n");
                if($order->operator_name != "" && $order->operator_name != null){
                    $impresora->text("Vendedor: $order->operator_name\n");
                }

                $payment_method = "";
                $payment_received = "no aplica";
                if($order->payment_method != "Yape"){
                    $payment_method = $order->payment_method == "CARD" ? "Pago con tarjeta" :  "Pago al contado";
                    if($order->payment_received != ""){
                        $payment_received = $order->payment_received;
                    }
                }else{
                    $payment_method = "Pago con $order->payment_method";
                }

                //si es delivery
                if($order->order_type == 1){
                    $payment_way = "Online";
                    $impresora->text("Tipo de pago: $payment_way\n");
                    //$impresora->text("Metodo de pago: $payment_method - $paid\n");
                    if($order->courier_name != null || $order->courier_name != ""){
                        $impresora->text("Motorizado: $order->courier_name\n");
                    }
                    $impresora->text("\n");
                    $impresora->text("================================================================\n");
                    $impresora->text("FIRMA:\n");
                    $impresora->text("================================================================\n");
                    $impresora->text("DNI:\n");
                }else{
                    // $payment_way = "Contraentrega";

                    // $impresora->text("Forma de pago: $payment_way\n");
                    // $impresora->text("Metodo de pago: $payment_method - $paid\n");
                    // $impresora->text("Efectivo: $payment_received\n");
                }
                $impresora->text("\n");
                $impresora->setJustification(Printer::JUSTIFY_CENTER);
                $impresora->text("Representación impresa de la \n$title_impresion\n");
                $impresora->text("Consulta tu boleta/factura de venta electrónica en:\n");
                
                $testStr ="https://ose.efact.pe/busca-tu-comprobante/consult.html";
                $impresora->setJustification(Printer::JUSTIFY_CENTER);
                $impresora->text("\n");
                $impresora->text("\n");
                $impresora -> qrCode($testStr, Printer::QR_ECLEVEL_L, 7);
                $impresora->text("\nhttps://www.efact.pe/\n");
                
            }else{
                $msjTotalDescuento = "DESCUENTO";
                $impresora->text($msjTotalDescuento);
                $descuento = number_format($order->discount, 2, '.', '');
                $espaciosCentro = 0;
                $espaciosCentro = self::CalculaEspacio($msjTotalDescuento,$descuento);
                $parteCentro = "";
                for ($i = 0; $i < $espaciosCentro; $i++) {
                    $parteCentro .= " ";
                }
                $impresora->text($parteCentro);
                $impresora->text("$descuento");
                $impresora->text("\n");
                $msjTotalPagar = "TOTAL A PAGAR S/";
                $impresora->text($msjTotalPagar);
                $totalPagar = number_format($order->total_price, 2, '.', '');
                $espaciosCentro = 0;
                $espaciosCentro = self::CalculaEspacio($msjTotalPagar,$totalPagar);
                $parteCentro = "";
                for ($i = 0; $i < $espaciosCentro; $i++) {
                    $parteCentro .= " ";
                }
                $impresora->text($parteCentro);
                $impresora->text("$totalPagar");
                $impresora->text("\n");

                //propina
                $first_tip = number_format($order->total_price * 0.10, 2, '.', ''); ;
                $second_tip = number_format($order->total_price * 0.15, 2, '.', ''); ;
                $third_tip = number_format($order->total_price * 0.20, 2, '.', ''); ;

                $impresora->text("\n");
                $text_mesa = "";
                $impresora->text("Mesa: ".$order->user_table);
                $impresora->text("\n");
                $impresora->text("PROPINA SUGERIDA");
                $impresora->text("\n");
                $impresora->text("10% = ".$first_tip."\n");
                $impresora->text("15% = ".$second_tip."\n");
                $impresora->text("20% = ".$third_tip."\n");
                $impresora->text("\n");
                $impresora->text("RUC / DNI :\n");
                $impresora->text("RAZON SOCIAL / NOMBRE\n");
            }

    
            $impresora->cut();
            $impresora->close();
            return response()->json(["message" => "IMPRESION DE TICKET DE VENTA"], 200 );
    
        } catch (\Throwable $th) {
            //throw $th;
            // Capturar mensaje del error
            $errorMessage = $th->getMessage();
            // Capturar el archivo donde ocurrió el error
            $errorFile = $th->getFile();
            // Capturar la línea donde ocurrió el error
            $errorLine = $th->getLine();
            $data = [
                "message" => "Error: {$errorMessage} en el archivo {$errorFile} en la línea {$errorLine}"
            ];
            Log::channel('stderr')->info("Error: {$errorMessage} en el archivo {$errorFile} en la línea {$errorLine}");
            return  $data;
        }
    }


    public static function ticketCocina($order,$items,$printer){
        $order = (object) $order;
        $items = (object) $items;
        $mesa = $order->user_table == null ? "" : " -- " . $order->user_table;
        //$mesa = $order->store_table;
        $type_delivery = "";
        switch ($order->order_type) {
            case '2':
                # code...
                $type_delivery = "RECOJO";
                break;
            
            case '3':
                # code...
                $type_delivery = "SALON";
                break;
            
            default:
                # code...
                $type_delivery = "DELIVERY";
                break;
        }
        if($order->store_area != null){
            $type_delivery = $order->store_area;
        }

        $numero = "";
        try {
            //code...
            if (isset($order->store_order_id)) {
            # code...
                $numero = $order->store_order_id != null ? $order->store_order_id : "";
            }
            if($printer["printer_ip"] != null){
                $ip = $printer["printer_ip"];
                $port = 9100;
                $connector = new NetworkPrintConnector($ip, $port);
            }else{
                $connector = new WindowsPrintConnector($printer["printer_name"]);
            }
            $impresora = new Printer($connector);
            $impresora->setJustification(Printer::JUSTIFY_LEFT);
            $impresora->setFont(PRINTER::FONT_A);
            $impresora->setTextSize(1,1);
            $impresora->setEmphasis(true);
            $uppercase = strtoupper($order->user_name);
            $impresora->text("CLIENTE: $uppercase - ".$type_delivery);
            $impresora->text("\n".$mesa);
            $impresora->setTextSize(1,1);
            $impresora->text("\n");
            $impresora->text("\n");
            $impresora->text("N° ORDEN: $numero". "   -  ".$order->source_app);
            $impresora->setJustification(Printer::JUSTIFY_CENTER);
            $impresora->setJustification(Printer::JUSTIFY_LEFT);
            $impresora->setEmphasis(true);
            $impresora->text("\n");
            $date =  date('d/m/Y', strtotime($order->created_at));
            $horaActual = date('H:i:s', strtotime($order->created_at));
            //contenido source
            $impresora->text("FECHA :$date HORA:$horaActual\n");
            $impresora->setEmphasis(true);
            $impresora->text("\n\n");
            $impresora->setTextSize(1,1);
            $index = 0;
            $auxItem = 0;
            $auxPromId = 0;

            foreach ($items as $item) {
                $item = (object) $item;
                $impresora->setFont(PRINTER::FONT_A);
                if(($item->item_id != $auxItem ) && ($item->promotion_id != null)){
                    $index ++;
                    $auxItem = $item->item_id;
                    $auxPromId = $item->promotion_id;
                    $nombre = mb_substr($item->promotion_name, 0, 40);
                    $nombre = strtoupper($nombre);
                    $precio = number_format($order->total_price, 2, '.', '');   
                    // Divide la línea en tres partes
                    $parteIzquierda = $nombre;// "$index >" . $nombre;
                    $parteCentro = "";
                    $parteDerecha = $precio;
                
                    // Calcula la cantidad de espacios entre las partes
                    $espaciosCentro = self::CalculaEspacio($parteIzquierda,$parteDerecha);
                    
                    // Alineación a la izquierda
                    $impresora->text($parteIzquierda);
                    
                    // Alineación central (agrega espacios en blanco)
                    for ($i = 0; $i < $espaciosCentro; $i++) {
                        $parteCentro .= " ";
                    }
                    $impresora->text($parteCentro);
                    
                    // Alineación a la derecha
                    $impresora->text("\n");
                    
                    $terms = $item->product_terms != null ? ' ('.$item->product_terms.')' : '';
                    $q_promo = $item->q_prom;
                    if($item->size_id == 9 || $item->size_id == 14) {
                        $arApp = ["ANDROID",'IOS','WEB'];
                        $source_app = strtoupper($order->source_app);
                        $q_total = 0;
                        if(in_array($source_app,$arApp) && $q_promo != 0){
                            $q_total = $item->quantity;
                        }else{
                            $q_total = $item->quantity;
                        }

                        $nombre_it = $q_total.''.' '.$item->product_name.$terms;
                    }else{
                        $arApp = ["ANDROID",'IOS','WEB'];
                        $source_app = strtoupper($order->source_app);
                        $q_total = 0;
                        if(in_array($source_app,$arApp)){
                            $q_total = $item->quantity;
                        }else{
                            $q_total = $item->quantity;
                        }
                        $arSizeName = explode('(',$item->size_name);
                        $nombre_it = $q_total.''.' '.$item->product_name.' '.$arSizeName[0].$terms;
                    }
                    $nombre_it = strtoupper($nombre_it);
                    //$impresora->setFont(PRINTER::FONT_B);
                    $impresora->text("$nombre_it");
                    if($item->notes != null){
                        //$impresora->setFont(PRINTER::FONT_B);
                        $impresora->text("Nota: ".$item->notes);
                    }
                    $impresora->setFont(PRINTER::FONT_A);

                }elseif($item->promotion_id == $auxPromId && $item->promotion_id != null){
                    $terms = $item->product_terms != null ? ' ('.$item->product_terms.')' : '';
                    $q_promo = $item->q_prom;
                    if($item->size_id == 9 || $item->size_id == 14) {
                        $arApp = ["ANDROID",'IOS','WEB'];
                        $source_app = strtoupper($order->source_app);
                        $q_total = 0;
                        if(in_array($source_app,$arApp) && $q_promo != 0){
                            $q_total = $item->quantity;
                        }else{
                            $q_total = $item->quantity;
                        }

                        $nombre_it = $q_total.''.' '.$item->product_name.$terms;
                    }else{
                        $arApp = ["ANDROID",'IOS','WEB'];
                        $source_app = strtoupper($order->source_app);
                        $q_total = 0;
                        if(in_array($source_app,$arApp) && $q_promo != 0){
                            $q_total = $item->quantity;
                        }else{
                            $q_total = $item->quantity;
                        }
                        $arSizeName = explode('(',$item->size_name);
                        $nombre_it = $q_total.''.' '.$item->product_name.' '.$arSizeName[0].$terms;
                    }
                    $nombre_it = strtoupper($nombre_it);
                    //$impresora->setFont(PRINTER::FONT_B);
                    $impresora->text("$nombre_it");
                    if($item->notes != null){
                        //$impresora->setFont(PRINTER::FONT_B);
                        $impresora->text("Nota: ".$item->notes);
                    }
                    $impresora->setFont(PRINTER::FONT_A);
                }else{
                    $auxItem = 0;
                }

                if($auxItem == 0){
                    $terms = $item->product_terms != null ? ' ('.$item->product_terms.')' : '';
                    if($item->size_id == 9 || $item->size_id == 14) {
                        $nombre = $item->quantity.''.' '.$item->product_name.$terms;
                    }else{
                        $arSizeName = explode('(',$item->size_name);
                        $nombre = $item->quantity.''.' '.$item->product_name.' '.$arSizeName[0].$terms;
                    }
                    $nombre = strtoupper($nombre);
                    $precio = number_format($item->price, 2, '.', '');
                    $index ++;
                    // Divide la línea en tres partes
                    $parteIzquierda = $nombre;//"$index >" . $nombre;
                    $parteCentro = "";
                    $parteDerecha = $precio;
                
                    // Calcula la cantidad de espacios entre las partes
                    $espaciosCentro = self::CalculaEspacio($parteIzquierda,$parteDerecha);
                    
                    // Alineación a la izquierda
                    $impresora->text($parteIzquierda);
                    
                    // Alineación central (agrega espacios en blanco)
                    for ($i = 0; $i < $espaciosCentro; $i++) {
                        $parteCentro .= " ";
                    }
                    $impresora->text($parteCentro);
                    
                    // Alineación a la derecha
                    //$impresora->text($parteDerecha);
                    if($item->notes != null){
                        //$impresora->setFont(PRINTER::FONT_B);
                        $impresora->text("Nota: ".$item->notes);
                    }
                    $impresora->setFont(PRINTER::FONT_A);
                    $impresora->text("\n");
                }
            

                
                
            }
            $impresora->setTextSize(1,1);
            $impresora->setFont(PRINTER::FONT_A);
            $impresora->text("\n");
            $impresora->text("OBS: \n");
            $impresora->text("$order->observation\n");
            
            $forma_pago = "";
            $payment_method = strtoupper($order->payment_method);
            $order->total_price = number_format($order->total_price, 2, '.', '');   
            if($payment_method != null){
                if($payment_method == "CASH"){
                    if($order->payment_received != null && $order->payment_received != ""){
                        if($order->payment_received == "Pago exacto"){
                            $forma_pago = "SOLES $order->total_price";
                        }else{
                            $vuelto = floatval($order->payment_received) - floatval($order->total_price);
                            $vuelto = number_format($vuelto, 2, '.', '');
                            $forma_pago = "SOLES $order->payment_received VUELTO:$vuelto";
                        }
                    }else{
                        $forma_pago = "SOLES $order->total_price";
                    }
                }elseif($payment_method == "CARD"){
                    if($order->payment_mp != null && $order->payment_mp != ""){
                        $forma_pago = "Tarjeta - $order->payment_mp - s/$order->total_price";
                    }else{
                        $forma_pago = "Tarjeta - s/$order->total_price";
                    }
                }elseif($payment_method == "PYA"){
                        $forma_pago = "PEDIDOSYA! - s/$order->total_price";
                }elseif($payment_method == "YAPE"){
                        $forma_pago = "YAPE - s/ $order->total_price";
                }else{
                    $change =  ($order->payment_with_cash + $order->payment_with_card) - $order->total_price;
                    $forma_pago = "MIXTO - E: s/$order->payment_with_cash - T: s/$order->payment_with_card ($order->payment_mp) - V: s/$change";
                }
            }
            
            $impresora->text("------------------------------------------------\n");
            $impresora->text("FORMA DE PAGO".' '."$forma_pago"."\n");
            $is_payment = $order->paid == 1 ? 'PAGADO' : 'POR PAGAR';
            $impresora->text("$is_payment\n");
            try {
                //$impresora->alarm(3,100); // Intentar activar la alarma
            } catch (\Throwable $th) {
                // Si no es compatible, simplemente ignorar el error
                $impresora->getPrintConnector()->write("\x07");
                //error_log("La impresora no admite alarm(). Continuando sin alarmas.");
            }

            //$testStr ="https://www.pizzaraul.work/";
            $impresora->setJustification(Printer::JUSTIFY_CENTER);
            $impresora->text("\n");
            $impresora->feed(2);
            $impresora->cut();
            $impresora->close();
            $data = [
                "message" => "IMPRESO CORRECTAMENTE"
            ];
        } catch (\Throwable $th) {
            //throw $th;
            $errorMessage = $th->getMessage();
            // Capturar el archivo donde ocurrió el error
            $errorFile = $th->getFile();
            // Capturar la línea donde ocurrió el error
            $errorLine = $th->getLine();
            $data = [
                "message" => "Error: {$errorMessage} en el archivo {$errorFile} en la línea {$errorLine}"
            ];
        }
        return $data;
    }
    public function ticketDeliveryDriver($order,$items,$store,$printer) {
        //$nombreImpresora = "$printer";
        $order = (object) $order;
        $items = (object) $items;
        $store = (object) $store;
        //$connector = new WindowsPrintConnector($nombreImpresora);
        try {

            if($printer["printer_ip"] != null){
                $ip = $printer["printer_ip"];
                $port = 9100;
                $connector = new NetworkPrintConnector($ip, $port);
            }else{
                $connector = new WindowsPrintConnector($printer["printer_name"]);
            }
            $impresora = new Printer($connector);
            $date = date('d-m-Y');
            $horaActual = date('h:i:s A');
            $impresora->setFont(PRINTER::FONT_B);
            $impresora->setJustification(Printer::JUSTIFY_CENTER);
            $impresora->setTextSize(1, 1);
            $impresora->setEmphasis(true);
            $impresora->text("PIZZA RAUL\n");   
            $impresora->setFont(PRINTER::FONT_B);
            $impresora->setEmphasis(false);
            $impresora->text("================================================================\n");
            $impresora->setJustification(Printer::JUSTIFY_LEFT);
            $impresora->setFont(PRINTER::FONT_B);
            //contenido source
            $impresora->text("PEDIDO N° $order->store_order_id\n"); 
            $impresora->text("Motorizado: $order->courier_name\n");
            $impresora->text("Orden de compra: $order->id\n");
            $impresora->text("Caja: 01\n FECHA: $date HORA : $horaActual\n");
            $impresora->setTextSize(1, 1);
            $impresora->text("================================================================\n");
            $impresora->setJustification(Printer::JUSTIFY_LEFT);
            //$impresora->text("Referencia: $order->reference \n");
            $impresora->text("================================================================\n");
    
            $impresora->text("i     Descripción                                             s/\n");
            $impresora->text("----------------------------------------------------------------\n");
    
            $index = 0;
            $auxItem = 0;
            $auxPromId = 0;
    
            foreach ($items as $item) {
                $item = (object) $item;
                $impresora->setFont(PRINTER::FONT_B);
                if(($item->item_id != $auxItem ) && ($item->promotion_id != null) ){
                    $index ++;
                    $auxItem = $item->item_id;
                    $auxPromId = $item->promotion_id;
                    $nombre = $item->promotion_name;
                    $precio = number_format($item->price, 2, '.', ''); 
                    $qprom = $index." > ".$item->q_prom."x ";
                    $order_value = $precio * $item->q_prom;
                    // Divide la línea en tres partes
                    $parteIzquierda = $qprom . $nombre;
                    $parteCentro = "";
                    $parteDerecha = $order_value;
                
                    // Calcula la cantidad de espacios entre las partes
                    $espaciosCentro = self::CalculaEspacio($parteIzquierda,$parteDerecha);
                    
                    // Alineación a la izquierda
                    $impresora->text($parteIzquierda);
                    
                    // Alineación central (agrega espacios en blanco)
                    for ($i = 0; $i < $espaciosCentro; $i++) {
                        $parteCentro .= " ";
                    }
                    $impresora->text($parteCentro);
                    
                    // Alineación a la derecha
                    $impresora->text($parteDerecha);
                    $impresora->text("\n");

                    
                    $terms = $item->product_terms != null ? ' ('.$item->product_terms.')' : '';
                    $q_promo = $item->q_prom;
                    if($item->size_id == 9 || $item->size_id == 14) {
                        $arApp = ["ANDROID",'IOS','WEB'];
                        $source_app = strtoupper($order->source_app);
                        $q_total = 0;
                        if(in_array($source_app,$arApp) && $q_promo != 0){
                            $q_total = $item->quantity;
                        }else{
                            $q_total = $item->quantity;
                        }

                        $nombre_it = $q_total.'x'.' '.$item->product_name.' '.$terms;
                    }else{
                        $arApp = ["ANDROID",'IOS','WEB'];
                        $source_app = strtoupper($order->source_app);
                        $q_total = 0;
                        if(in_array($source_app,$arApp) && $q_promo != 0){
                            $q_total = $item->quantity;
                        }else{
                            $q_total = $item->quantity;
                        }
                        $arSizeName = explode('(',$item->size_name);
                        $nombre_it = $q_total.'x'.' '.$item->product_name.' '.$terms.' '.$arSizeName[0];
                    }

                    //$nombre_it = $item->quantity.'x'.' '.$item->product_name.' '.$item->size_name;
                    $impresora->setFont(PRINTER::FONT_B);
                    $impresora->text("  >> $nombre_it \n");

                }elseif($item->promotion_id == $auxPromId && $item->promotion_id != null){
                    
                    $terms = $item->product_terms != null ? ' ('.$item->product_terms.')' : '';
                    $q_promo = $item->q_prom;
                    if($item->size_id == 9 || $item->size_id == 14) {
                        $arApp = ["ANDROID",'IOS','WEB'];
                        $source_app = strtoupper($order->source_app);
                        $q_total = 0;
                        if(in_array($source_app,$arApp) && $q_promo != 0){
                            $q_total = $item->quantity;
                        }else{
                            $q_total = $item->quantity;
                        }

                        $nombre_it = $q_total.'x'.' '.$item->product_name.' '.$terms;
                    }else{
                        $arApp = ["ANDROID",'IOS','WEB'];
                        $source_app = strtoupper($order->source_app);
                        $q_total = 0;
                        if(in_array($source_app,$arApp) && $q_promo != 0){
                            $q_total = $item->quantity;
                        }else{
                            $q_total = $item->quantity;
                        }
                        $arSizeName = explode('(',$item->size_name);
                        $nombre_it = $q_total.'x'.' '.$item->product_name.' '.$terms.' '.$arSizeName[0];
                    }

                    //$nombre_it = $item->quantity.'x'.' '.$item->product_name.' '.$item->size_name;
                    $impresora->setFont(PRINTER::FONT_B);
                    $impresora->text("  >> $nombre_it \n");
                }else{
                    $auxItem = 0;
                }

                if($auxItem == 0){
                    $size_name = "";

                    if($item->size_id == 9 || $item->size_id == 14) {
                        $terms = $item->product_terms != null ? ' ('.$item->product_terms.')' : '';
                        $nombre = $item->quantity.'x'.' '.$item->product_name.' '.$terms;
                    }else{
                        $terms = $item->product_terms != null ? ' ('.$item->product_terms.')' : '';
                        $arSizeName = explode('(',$item->size_name);
                        $nombre = $item->quantity.'x'.' '.$item->product_name.' '.$terms.' '.$arSizeName[0];
                    }
                    $precio = number_format($item->price, 2, '.', '');
                    $discount = $item->discount;
                    $order_value = $precio * $item->quantity - $discount;
                    if($order_value == 0 || $order_value == 0.00){
                        $order_value = "";
                    }else{
                        $order_value = number_format($order_value, 2, '.', '');
                    }
                    $index ++;
                    // Divide la línea en tres partes
                    $parteIzquierda = "$index >" . $nombre;
                    $parteCentro = "";
                    $parteDerecha = $order_value;
                
                    // Calcula la cantidad de espacios entre las partes
                    $espaciosCentro = self::CalculaEspacio($parteIzquierda,$parteDerecha);
                    
                    // Alineación a la izquierda
                    $impresora->text($parteIzquierda);
                    
                    // Alineación central (agrega espacios en blanco)
                    for ($i = 0; $i < $espaciosCentro; $i++) {
                        $parteCentro .= " ";
                    }
                    $impresora->text($parteCentro);
                    
                    // Alineación a la derecha
                    $impresora->text($parteDerecha);
                    $impresora->text("\n");
                }
            
    
            }
    
            $igv = $store->cfd_igv;
            $igv = number_format($igv, 2, '.', '');
            $porIgv = $igv / 100;
            $porIgv = doubleval($porIgv);

            $impresora->setFont(PRINTER::FONT_B);
            $order_sin_impuesto = $order->total_price / ( 1 + $porIgv );
            $order_sin_impuesto =  round($order_sin_impuesto, 2);

            $msjOP = "OP. GRAVADAS: S/";
            $impresora->text($msjOP);
            $espaciosCentro = 0;
            $espaciosCentro = self::CalculaEspacio($msjOP,$order_sin_impuesto);
            $parteCentro = "";
            for ($i = 0; $i < $espaciosCentro; $i++) {
                $parteCentro .= " ";
            }
            $impresora->text($parteCentro);
            $impresora->text("$order_sin_impuesto");
            $impresora->text("\n");


            
            $msjIgv = "IGV ($igv%): S/";
            $impresora->text($msjIgv);
            $order_impuesto = $order_sin_impuesto * $porIgv;
            $order_impuesto = round($order_impuesto, 2);
            $espaciosCentro = 0;
            $espaciosCentro = self::CalculaEspacio($msjIgv,$order_impuesto);
            $parteCentro = "";
            for ($i = 0; $i < $espaciosCentro; $i++) {
                $parteCentro .= " ";
            }
            $impresora->text($parteCentro);
            $impresora->text("$order_impuesto");
            $impresora->text("\n");
    
            $msjTotalPagar = "TOTAL A PAGAR S/";
            $impresora->text($msjTotalPagar);
            $totalPagar = number_format($order->total_price, 2, '.', '');
            $espaciosCentro = 0;
            $espaciosCentro = self::CalculaEspacio($msjTotalPagar,$totalPagar);
            $parteCentro = "";
            for ($i = 0; $i < $espaciosCentro; $i++) {
                $parteCentro .= " ";
            }
            $impresora->text($parteCentro);
            $impresora->text("$totalPagar");
            $impresora->text("\n");
    
            $impresora->setFont(PRINTER::FONT_B);
            $impresora->text("================================================================\n");;
            $impresora->text("Información Adicional\n");
            $impresora->text("N° de pedido de tienda: $order->store_order_id\n");

            $forma_pago = "";
            $payment_method = strtoupper($order->payment_method);
            $order->total_price = number_format($order->total_price, 2, '.', '');
            if($payment_method == "CASH"){
                if($order->payment_received != null && $order->payment_received != ""){
                    if($order->payment_received == "Pago exacto" || $order->payment_received == "Pagará exacto"){
                        $forma_pago = "SOLES $order->total_price";
                    }else{
                        $vuelto = floatval($order->payment_received) - floatval($order->total_price);
                        $vuelto = number_format($vuelto, 2, '.', '');
                        $forma_pago = "SOLES $order->payment_received VUELTO:$vuelto";
                    }
                }else{
                    $forma_pago = "SOLES $order->total_price";
                }
            }elseif($payment_method == "CARD"){
                if($order->payment_mp != null && $order->payment_mp != ""){
                    $forma_pago = "Tarjeta - $order->payment_mp - s/$order->total_price";
                }else{
                    $forma_pago = "Tarjeta - s/$order->total_price";
                }
            }elseif($payment_method == "YAPE"){
                    $forma_pago = "YAPE - s/$order->total_price";
            }elseif($payment_method == "PYA"){
                $forma_pago = "PEDIDOSYA! - s/$order->total_price";
            }else{
                $order->payment_with_cash = number_format($order->payment_with_cash, 2, '.', '');
                $order->payment_with_card = number_format($order->payment_with_card, 2, '.', '');
                $change =  ($order->payment_with_cash + $order->payment_with_card) - $order->total_price;
                $forma_pago = "MIXTO - E: s/$order->payment_with_cash - T: s/$order->payment_with_card ($order->payment_mp) - V: s/$change";
            }

            $impresora->text("FORMA DE PAGO".' '."$forma_pago"."\n");
            $impresora->text("CLIENTE: $order->user_name\n");
            $direccion = $order->street_name." ".$order->street_number;

            if($order->flat_number != null){
                $direccion.="-dpto: $order->flat_number";
            }

            if($order->urbanization != null){
                $direccion.=" - urb: $order->urbanization";
            }

            if($order->block != null){
                $direccion.="- blq : $order->block";
            }

            if($order->lot_number != null){
                $direccion.="- lote : $order->lot_number";
            }


            $impresora->text("DIRECCIÓN:\n");
            $impresora->text("$direccion\n");
            $impresora->text("REFERENCIA: \n");
            $impresora->text("$order->reference\n");
            $impresora->text("TELEFONO: $order->user_phone\n");

            
            $impresora->text("OBSERVACION: $order->observation\n");
            $impresora->text("Canal: $order->source_app\n");
            $impresora->text("Cliente: $order->user_name\n");
            if($order->operator_name != "" && $order->operator_name != null){
                $impresora->text("Vendedor: $order->operator_name\n");
            }

            //si es delivery
            if($order->order_type == 1){
                $payment_way = "Online";
                //$impresora->text("TIPO DE PAGO: $payment_way\n");
            }

            $impresora->text("\n");
            //IDMOTORIZADO-IDPEDIDO-FECHAACTUAL
            $testStr ="$order->courier_id"."_".$order->id."_".$date;
            $impresora->setJustification(Printer::JUSTIFY_CENTER);
            $impresora->text("\n");
            $impresora->text("\n");
            $impresora -> qrCode($testStr, Printer::QR_ECLEVEL_L, 7);
            $impresora->text("\nAsigna en tu aplicativo de motorizado\n");
            $impresora->cut();
            $impresora->close();
            return response()->json(["message" => "IMPRESION DE TICKET DE VENTA"], 200 );
        } catch (\Throwable $th) {
            //throw $th;
            // Capturar mensaje del error
            $errorMessage = $th->getMessage();
            // Capturar el archivo donde ocurrió el error
            $errorFile = $th->getFile();
            // Capturar la línea donde ocurrió el error
            $errorLine = $th->getLine();
            $data = [
                "message" => "Error: {$errorMessage} en el archivo {$errorFile} en la línea {$errorLine}"
            ];
            Log::channel('stderr')->info("Error: {$errorMessage} en el archivo {$errorFile} en la línea {$errorLine}");
            return  $data;
        }
    }
    
    public function ticketCierreCaja($store,$apertura_s,$suma_S,$ventas,$transactions_S,$usuario,$store_balance,$mercaderia,$printer) {
        //$nombreImpresora = "$printer";
        $store = (object) $store;
        $suma_S = (object) $suma_S;
        if($ventas != null){
            $ventas = (object) $ventas;
        }else{
            $ventas = null;
        }
        $usuario = (object) $usuario;
        $transactions_S = (object) $transactions_S;
        $store_balance = (object) $store_balance;
        $mercaderia = (object) $mercaderia;
        $apertura_s = (object) $apertura_s;
        try {

            if($printer["printer_ip"] != null){
                $ip = $printer["printer_ip"];
                $port = 9100;
                $connector = new NetworkPrintConnector($ip, $port);
            }else{
                $connector = new WindowsPrintConnector($printer["printer_name"]);
            }
            $impresora = new Printer($connector);
            $date = date('d/m/Y');
            $horaActual = date('h:i:s A');
            $impresora->setEmphasis(true);
            $impresora->setFont(PRINTER::FONT_A);
            
            $impresora->setJustification(Printer::JUSTIFY_CENTER);
            $impresora->text("$store->title\n");   
            $impresora->text("RUC: $store->nro_ruc\n");   
            $impresora->text("$store->razon_social\n");   
            $impresora->text("\n");   
            $impresora->setFont(PRINTER::FONT_B);
            $impresora->setJustification(Printer::JUSTIFY_LEFT);

            $impresora->text("Cierre de caja");
            $impresora->text("\n");
            $impresora->text("\n");

            $impresora->setJustification(Printer::JUSTIFY_LEFT);
            
            $store_name = "TIENDA ".$store->title;
            $impresora->text("$store_name\n");   
            
            $date_string = "FECHA: ".$date." Hora: ".$horaActual;
            $impresora->text("$date_string\n");           
            

            $impresora->setJustification(Printer::JUSTIFY_LEFT);

            $impresora->text("\n");
            //-------------------------------------

            $name = "SALDO INICIAL: ";
            $maxLength = 45;
            $shortname = substr($name, 0, $maxLength);
            $limit_first = 48;
            $first = str_pad($shortname, $limit_first);

            $name2 = $apertura_s->amount;
            $name2 = number_format($name2, 2);
            $maxLength = 8;
            $shortname = substr($name2, 0, $maxLength);
            $limit_first = 8;
            $two = str_pad($shortname, $limit_first);

            $saldo_inicial = $first.$two;
            $impresora->text("$saldo_inicial\n");   

            //-------------------------------------

            $name = "VENTAS: ";
            $maxLength = 45;
            $shortname = substr($name, 0, $maxLength);
            $limit_first = 48;
            $first = str_pad($shortname, $limit_first);

            $name2 = $store_balance->balance_opening;
            $name2 = number_format($name2, 2);
            $venta_save = $name2;
            $maxLength = 8;
            $shortname = substr($name2, 0, $maxLength);
            $limit_first = 8;
            $two = str_pad($shortname, $limit_first);

            $ventas_st = $first.$two;
            $impresora->text("$ventas_st\n");   

            //-------------------------------------

            $name = "DEPOSITO EFECTIVO: ";
            $maxLength = 45;
            $shortname = substr($name, 0, $maxLength);
            $limit_first = 48;
            $first = str_pad($shortname, $limit_first);

            if($ventas == null){
                $name2 = 0.00;
                $name2 = number_format($name2, 2);
            }else{
                $name2 = $ventas->amount;
                $name2 = number_format($name2, 2);
            }
            $maxLength = 8;
            $shortname = substr($name2, 0, $maxLength);
            $limit_first = 8;
            $two = str_pad($shortname, $limit_first);

            $ventas_st = $first.$two;
            $impresora->text("$ventas_st\n");   

            //-------------------------------------

            $name = "SALDO EN CAJA: ";
            $maxLength = 45;
            $shortname = substr($name, 0, $maxLength);
            $limit_first = 48;
            $first = str_pad($shortname, $limit_first);

            $name2 = $store_balance->sales_total;
            $name2 = number_format($name2, 2);
            $maxLength = 8;
            $shortname = substr($name2, 0, $maxLength);
            $limit_first = 8;
            $two = str_pad($shortname, $limit_first);

            $ventas_st = $first.$two;
            $impresora->text("$ventas_st\n");   

            //-------------------------------------

            $impresora->text("\n");
            $impresora->setEmphasis(true);
            $name = "MOVIMIENTOS DE CAJA: EGRESOS ";
            $maxLength = 45;
            $shortname = substr($name, 0, $maxLength);
            $limit_first = 48;
            $first = str_pad($shortname, $limit_first);

            $impresora->text("$first\n");   
            $impresora->text("\n");
            $impresora->setEmphasis(true);

            foreach ($transactions_S as $key) {
                # code...
                $key = (object) $key;
                $name = "$key->category_trx_name";
                $maxLength = 45;
                $shortname = substr($name, 0, $maxLength);
                $limit_first = 48;
                $first = str_pad($shortname, $limit_first);

                $name2 = $key->amount;
                $name2 = number_format($name2, 2);
                $maxLength = 8;
                $shortname = substr($name2, 0, $maxLength);
                $limit_first = 8;
                $two = str_pad($shortname, $limit_first);
                $ventas_st = $first.$two;
                $impresora->text("$ventas_st\n");   
            }

            //----------------MERCADERIA---------------------

            $impresora->text("\n");
            $impresora->setEmphasis(true);
            $name = "MERCADERIA ";
            $maxLength = 45;
            $shortname = substr($name, 0, $maxLength);
            $limit_first = 48;
            $first = str_pad($shortname, $limit_first);

            $impresora->text("$first\n");   
            $impresora->text("\n");
            $impresora->setEmphasis(true);
            
            //-------------------------------------

            $name = "SALDO INICIAL EN MERCADERIA: ";
            $maxLength = 45;
            $shortname = substr($name, 0, $maxLength);
            $limit_first = 48;
            $first = str_pad($shortname, $limit_first);

            $name2 = $mercaderia->saldoInicial;
            $name2 = number_format($name2, 2);
            $maxLength = 8;
            $shortname = substr($name2, 0, $maxLength);
            $limit_first = 8;
            $two = str_pad($shortname, $limit_first);

            $ventas_st = $first.$two;
            $impresora->text("$ventas_st\n");   

            //-------------------------------------

            $name = "DESPACHO ALMACEN: ";
            $maxLength = 45;
            $shortname = substr($name, 0, $maxLength);
            $limit_first = 48;
            $first = str_pad($shortname, $limit_first);

            $name2 = $mercaderia->totalDeAlmacenes;
            $name2 = number_format($name2, 2);
            $maxLength = 8;
            $shortname = substr($name2, 0, $maxLength);
            $limit_first = 8;
            $two = str_pad($shortname, $limit_first);

            $ventas_st = $first.$two;
            $impresora->text("$ventas_st\n");   

            //-------------------------------------

            $name = "DESPACHO TIENDA: ";
            $maxLength = 45;
            $shortname = substr($name, 0, $maxLength);
            $limit_first = 48;
            $first = str_pad($shortname, $limit_first);

            $name2 = $mercaderia->totalEntradaDeTienda;
            $name2 = number_format($name2, 2);
            $maxLength = 8;
            $shortname = substr($name2, 0, $maxLength);
            $limit_first = 8;
            $two = str_pad($shortname, $limit_first);

            $ventas_st = $first.$two;
            $impresora->text("$ventas_st\n");   

            //-------------------------------------

            $name = "SALIDA TIENDA: ";
            $maxLength = 45;
            $shortname = substr($name, 0, $maxLength);
            $limit_first = 48;
            $first = str_pad($shortname, $limit_first);

            $name2 = $mercaderia->totalDespachoATienda;
            $name2 = number_format($name2, 2);
            $maxLength = 8;
            $shortname = substr($name2, 0, $maxLength);
            $limit_first = 8;
            $two = str_pad($shortname, $limit_first);

            $ventas_st = $first.$two;
            $impresora->text("$ventas_st\n");   



            //-------------------------------------

            $name = "CONSUMO: ";
            $maxLength = 45;
            $shortname = substr($name, 0, $maxLength);
            $limit_first = 48;
            $first = str_pad($shortname, $limit_first);

            

            $name2 = $mercaderia->saldoInicial + $mercaderia->totalDeAlmacenes  + $mercaderia->totalEntradaDeTienda - $mercaderia->totalDespachoATienda - $mercaderia->saldoFinal;

            $name2 = doubleval($name2);
            $name2 = number_format($name2, 2);
            $consumo_save = $name2;
            $maxLength = 8;
            $shortname = substr($name2, 0, $maxLength);
            $limit_first = 8;
            $two = str_pad($shortname, $limit_first);

            $ventas_st = $first.$two;
            $impresora->text("$ventas_st\n");   

            //-------------------------------------

            $impresora->setEmphasis(true);
            $name = "SALDO FINAL MERCADERIA: ";
            $maxLength = 45;
            $shortname = substr($name, 0, $maxLength);
            $limit_first = 48;
            $first = str_pad($shortname, $limit_first);

            $name2 = doubleval($mercaderia->saldoFinal);
            $name2 = number_format($name2, 2);
            $maxLength = 8;
            $shortname = substr($name2, 0, $maxLength);
            $limit_first = 8;
            $two = str_pad($shortname, $limit_first);

            $ventas_st = $first.$two;
            $impresora->text("$ventas_st\n");   

            //-------------------------------------

            $impresora->text("\n");   
            $impresora->setEmphasis(true);
            $name = "RATIO DE TIENDA: ";
            $maxLength = 45;
            $shortname = substr($name, 0, $maxLength);
            $limit_first = 48;
            $first = str_pad($shortname, $limit_first);

            $name2 = (doubleval($consumo_save) / doubleval($venta_save)) * 100 ;
            $name2 = doubleval($name2);
            //$name2 = number_format($name2, 2) . "%";
            $maxLength = 8;
            $shortname = substr($name2, 0, $maxLength);
            $limit_first = 8;
            $two = str_pad($shortname, $limit_first);

            $ventas_st = $first.$two;
            $impresora->text("$ventas_st\n");   


            //-------------------------------------

            $impresora->text("\n");
            $impresora->cut();
            $impresora->close();
            return response()->json(["message" => "IMPRESION DE TICKET DE VENTA"], 200 );
        } catch (\Throwable $th) {
            //throw $th;
            // Capturar mensaje del error
            $errorMessage = $th->getMessage();
            // Capturar el archivo donde ocurrió el error
            $errorFile = $th->getFile();
            // Capturar la línea donde ocurrió el error
            $errorLine = $th->getLine();
            $data = [
                "message" => "Error: {$errorMessage} en el archivo {$errorFile} en la línea {$errorLine}"
            ];
            Log::channel('stderr')->info("Error: {$errorMessage} en el archivo {$errorFile} en la línea {$errorLine}");
            return  $data;
        }
    }

    public function ticketPaloteo($store,$data,$printer){
        $store = (object) $store;
        $data = (object) $data;
        //$nombreImpresora = "$printer";
        try {

            if($printer["printer_ip"] != null){
                $ip = $printer["printer_ip"];
                $port = 9100;
                $connector = new NetworkPrintConnector($ip, $port);
            }else{
                $connector = new WindowsPrintConnector($printer["printer_name"]);
            }
            $impresora = new Printer($connector);
            
            $impresora->setJustification(Printer::JUSTIFY_CENTER);
            $impresora->text("$store->title\n");   
            $impresora->text("RUC: $store->nro_ruc\n");   
            $impresora->text("$store->razon_social\n");   
            $impresora->text("\n");   
            $impresora->setFont(PRINTER::FONT_B);
            $impresora->setJustification(Printer::JUSTIFY_LEFT);

            $impresora->text("Paloteo de ventas");
            $impresora->text("\n");
            $impresora->text("\n");
            $auxTitleCat = "";
            $totalPaloteo = 0.00;
            foreach ($data as $key) {
                # code...
                $key = (object) $key;
                $impresora->setEmphasis(true);
                if($auxTitleCat == ""){
                    $auxTitleCat = $key->Categoria;
                    $impresora->text($auxTitleCat);
                    $impresora->text("\n");
                }else{
                    if($auxTitleCat != $key->Categoria) {
                        $auxTitleCat = $key->Categoria;
                        $impresora->text("\n");
                        $impresora->text("\n");
                        $impresora->text($auxTitleCat);
                        $impresora->text("\n");
                    }
                }
                $impresora->setEmphasis(false);
                ##product name
                $impresora->text("\n");
                $size_name = "";
                if($key->Tamano != "Sin tamano"){
                    $size_name = $key->Tamano;
                }
                $name = $key->Descripcion."  ".$size_name;
                $maxLength = 45;
                $shortname = substr($name, 0, $maxLength);
                $limit_first = 48;
                $first = str_pad($shortname, $limit_first);
                ##size -- 6 -- limit 10
                // $sizename = $key->Tamano;
                // $maxLengthSize = 3;
                // $shortsizename = substr($sizename, 0, $maxLengthSize);
                // $limit_size = 6;
                // $two = str_pad($shortsizename, $limit_size);
                ##quantity
                $quatity_name = $key->Cantidad;
                $maxLengthquatity = 3;
                $shortnamequatity = substr($quatity_name, 0, $maxLengthquatity);
                $limit_quatity = 6;
                $three = str_pad($shortnamequatity, $limit_quatity);
                ##unit price
                /* $unit_price = 0.00;
                if(intval($key->Cantidad) > 0){
                    $unit_price = ($key->PrecioTotal) / $key->Cantidad;
                }
                $formattedValue = number_format($unit_price, 2, '.', '');
                $maxLengthunit_price = 6;
                $shortnameunit_price = substr($formattedValue, 0, $maxLengthunit_price);
                $limit_quatity = 8;
                $four = str_pad($shortnameunit_price, $limit_quatity); */

                ##total_price
                $formattedValue = number_format($key->PrecioTotal, 2, '.', '');
                $maxLengthunit_price = 6;
                $shortnameunit_price = substr($formattedValue, 0, $maxLengthunit_price);
                $limit_quatity = 8;
                $five = str_pad($shortnameunit_price, $limit_quatity);
                $totalPaloteo = $totalPaloteo + $key->PrecioTotal;
                // final text
                $final_text = $first.$three.$five;
                $impresora->text($final_text);

            }

            $impresora->setFont(PRINTER::FONT_A);
            $impresora->setEmphasis(true);
            $name = "Total";
            $maxLength = 5;
            $shortname = substr($name, 0, $maxLength);
            $limit_first = 35;
            $first = str_pad($shortname, $limit_first);

            $formattedValue = number_format($totalPaloteo, 2, '.', '');
            $maxLengthunit_price = 6;
            $shortnameunit_price = substr($formattedValue, 0, $maxLengthunit_price);
            $limit_quatity = 8;
            $five = str_pad($shortnameunit_price, $limit_quatity);
            $five = "s/. ".$five;
            $impresora->text("\n");
            $impresora->text("\n");
            $final_text = $first.$five;
            $impresora->text($final_text);
            $impresora->text("\n");
            $impresora->feed(2);
            $impresora->cut();
            $impresora->close();
            return response()->json(["message" => "IMPRESION DE TICKET DE VENTA"], 200 );
        } catch (\Throwable $th) {
            //throw $th;
            // Capturar mensaje del error
            $errorMessage = $th->getMessage();
            // Capturar el archivo donde ocurrió el error
            $errorFile = $th->getFile();
            // Capturar la línea donde ocurrió el error
            $errorLine = $th->getLine();
            $data = [
                "message" => "Error: {$errorMessage} en el archivo {$errorFile} en la línea {$errorLine}"
            ];
            Log::channel('stderr')->info("Error: {$errorMessage} en el archivo {$errorFile} en la línea {$errorLine}");
            return  $data;
        }
    }

    public function ticketInventario($store,$data,$printer){
        $store = (object) $store;
        //$nombreImpresora = "$printer";
        try {

            if($printer["printer_ip"] != null){
                $ip = $printer["printer_ip"];
                $port = 9100;
                $connector = new NetworkPrintConnector($ip, $port);
            }else{
                $connector = new WindowsPrintConnector($printer["printer_name"]);
            }
            $impresora = new Printer($connector);
            
            $impresora->setJustification(Printer::JUSTIFY_CENTER);

            $impresora->text("$store->title\n");   
            $impresora->text("RUC: $store->nro_ruc\n");   
            $impresora->text("$store->razon_social\n");   
            $impresora->setFont(PRINTER::FONT_B);
            $impresora->setJustification(Printer::JUSTIFY_LEFT);
            $impresora->text("\n");   
            $impresora->text("Inventario");
            $impresora->text("\n");
            $impresora->text("\n");
            $auxTitleCat = "";
            foreach ($data as $key) {
                # code...
                $key = (object) $key;

                $impresora->setEmphasis(true);
                if($auxTitleCat == ""){
                    $auxTitleCat = $key->categoryName;
                    $impresora->text($auxTitleCat);
                    $impresora->text("\n");
                }else{
                    if($auxTitleCat != $key->categoryName) {
                        $auxTitleCat = $key->categoryName;
                        $impresora->text("\n");
                        $impresora->text("\n");
                        $impresora->text($auxTitleCat);
                        $impresora->text("\n");
                    }
                }
                $impresora->setEmphasis(false);
                ##product name
                $impresora->text("\n");
                $name = $key->item_name;
                $maxLength = 50;
                $shortname = substr($name, 0, $maxLength);
                $limit_first = 55;
                $first = str_pad($shortname, $limit_first);
                ##size -- 6 -- limit 10
                $sizename = $key->stock_physical;
                $sizename = number_format($sizename, 3);
                $maxLengthSize = 6;
                $shortsizename = substr($sizename, 0, $maxLengthSize);
                $limit_size = 8;
                $two = str_pad($shortsizename, $limit_size);
                
                // final text
                $final_text = $first.$two;
                $impresora->text($final_text);

            }

            $impresora->text("\n");
            $impresora->feed(2);
            $impresora->cut();
            $impresora->close();
            return response()->json(["message" => "IMPRESION DE TICKET DE VENTA"], 200 );
        } catch (\Throwable $th) {
            //throw $th;
            // Capturar mensaje del error
            $errorMessage = $th->getMessage();
            // Capturar el archivo donde ocurrió el error
            $errorFile = $th->getFile();
            // Capturar la línea donde ocurrió el error
            $errorLine = $th->getLine();
            $data = [
                "message" => "Error: {$errorMessage} en el archivo {$errorFile} en la línea {$errorLine}"
            ];
            Log::channel('stderr')->info("Error: {$errorMessage} en el archivo {$errorFile} en la línea {$errorLine}");
            return  $data;
        }
    }

    public function ticketMovimiento($movimiento,$store,$printer) {
        $movimiento = (object) $movimiento;
        $store = (object) $store;
        //$nombreImpresora = "$printer";
        try {
            if($printer["printer_ip"] != null){
                $ip = $printer["printer_ip"];
                $port = 9100;
                $connector = new NetworkPrintConnector($ip, $port);
            }else{
                $connector = new WindowsPrintConnector($printer["printer_name"]);
            }
            $impresora = new Printer($connector);
            
            $impresora->setJustification(Printer::JUSTIFY_CENTER);
            $impresora->text("$store->title\n");   
            $impresora->text("RUC: $store->nro_ruc\n");   
            $impresora->text("$store->razon_social\n");   
            $impresora->text("\n");   
            $impresora->setFont(PRINTER::FONT_B);
            $impresora->setJustification(Printer::JUSTIFY_LEFT);
            $date = $movimiento->transaction_at;
            $date_string = "FECHA DE MOVIMIENTO: ".$date;
            $impresora->text("$date_string\n");           
            $impresora->setJustification(Printer::JUSTIFY_LEFT);
            $impresora->text("\n");
            //-------------------------------------

            $name = $movimiento->category_trx_name;
            $maxLength = 45;
            $shortname = substr($name, 0, $maxLength);
            $limit_first = 48;
            $first = str_pad($shortname, $limit_first);

            $name2 = $movimiento->amount;
            $name2 = number_format($name2, 2);
            $maxLength = 8;
            $shortname = substr($name2, 0, $maxLength);
            $limit_first = 8;
            $two = str_pad($shortname, $limit_first);

            $saldo_inicial = $first.$two;
            $impresora->text("$saldo_inicial\n");   

            $impresora->text("\n");
            $impresora->cut();
            $impresora->close();

            //-------------------------------------
        } catch (\Throwable $th) {
            // Capturar mensaje del error
            $errorMessage = $th->getMessage();
            // Capturar el archivo donde ocurrió el error
            $errorFile = $th->getFile();
            // Capturar la línea donde ocurrió el error
            $errorLine = $th->getLine();
            $data = [
                "message" => "Error: {$errorMessage} en el archivo {$errorFile} en la línea {$errorLine}"
            ];
            Log::channel('stderr')->info("Error: {$errorMessage} en el archivo {$errorFile} en la línea {$errorLine}");
            return  $data;
            //throw $th;
        }
    }

    function pruebaQr() {
        
        $connector = new WindowsPrintConnector("POS-80C");
        $impresora = new Printer($connector);
        $testStr ="1436_43567_2024-10-24";
        $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $impresora->text("\n");
        $impresora->text("\n");
        $impresora -> qrCode($testStr, Printer::QR_ECLEVEL_L, 7);
        //IDMOTORIZADO-IDPEDIDO-FECHAACTUAL
        $impresora->text("\n1436_43567_2024-10-24\n");
        $impresora->cut();
        $impresora->close();
        return response()->json(["message" => "IMPRESION DE TICKET DE VENTA"], 200 );
    }

    
    public static function CalculaEspacio($left, $right)  {
        $espaciosCentro = 64 - strlen($left) - strlen($right);
        return $espaciosCentro;
    }
}
