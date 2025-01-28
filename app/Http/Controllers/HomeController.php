<?php

namespace App\Http\Controllers;

use App\Models\StoreLogin;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    private $store;
    public function __construct() {
        $store = StoreLogin::orderBy('id','desc')->first();
        if($store->cookie_encrypt == null){
            $store->cookie_encrypt = $_COOKIE["store_id"];
            $store->save();
        }
        $this->store = $store;
    }

    //
    function home() : View {
        return view('home')->with('store',$this->store);
    }
}
