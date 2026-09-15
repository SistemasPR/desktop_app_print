<?php

namespace App\Http\Controllers;

use App\Models\StoreLogin;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{

    function configuration() {
        if(isset($_COOKIE["Secure-PR-POS-PRINT"])){
            return redirect()->action([HomeController::class, 'home']);
        }

        return view('configuration');
    }

    //
    function home() : View {
        if(isset($_COOKIE["Secure-PR-POS-PRINT"])){
            $store_id = $_COOKIE["Secure-PR-POS-PRINT"];
            return view('home_v2')->with('store_id',$store_id);
        }
        return view('configuration');
    }
}
