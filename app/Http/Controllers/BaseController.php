<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function get_city_by_prefecture(Request $request){
        for($k = 0; $k < count(config('global.pref_city')); $k++){
            if($request->pref == config('global.pref_city')[$k]["id"]){
                return config('global.pref_city')[$k]["city"];
            }
        }
    }
}
