<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\historiquetransanction;


class historiquetransaction extends Controller
{

public function viewtransaction(){
    $data=historiquetransanction::all();
    return view('superadmin.historiquetransaction',compact('data'));
}
}
