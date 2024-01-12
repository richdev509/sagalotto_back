<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\api\verificationController as verify;

class ticketController extends Controller
{
    public function creer_ticket(Request $request){
       foreach($request->input('tirages') as $value){

          $t[] = $value;
       }
       dd($t);
 
     
      
     }
    
}
