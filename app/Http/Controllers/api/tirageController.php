<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\vendeur;

use Tymon\JWTAuth\Contracts\Providers\Auth as ProvidersAuth;


class tirageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function tirage(){

    }
    

}
