<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class rapportController extends Controller
{
    public function create_rapport(){
        if (Session('loginId')) {
            $tirage = DB::table('tirage')
            ->leftJoin('tirage_record', 'tirage_record.tirage_id', '=', 'tirage.id')
            ->whereNull('tirage_record.tirage_id')
            ->select('tirage.*')
            ->get();
            return view('ajouter_tirage', ['tirage'=>$tirage]);
        } else {
            return view('login');
        }

    }
}
