<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
class tirageController extends Controller
{
    //

    public function create(){
       return response()->json(Carbon::now()->format('H:i:s'));
      /*  if (Session('loginId')) {
            
            return view('ajouter_tirage');
        } else {
            return view('login');
        }
        */

    }
}
