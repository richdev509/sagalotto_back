<?php

namespace App\Http\Controllers;

use App\Models\tirage_record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
class tirageController extends Controller
{
    //

    public function create(Request $request){
        
        if (Session('loginId')) {
            $tirage = DB::table('tirage')->where([
              
            ])->get();
            return view('ajouter_tirage', ['tirage'=>$tirage]);
        } else {
            return view('login');
        }

    }
    public function store(Request $request){
        
        if (Session('loginId')) {
            $validator = $request->validate([
                "tirage" => "required",
                'time' =>'required',
                'time_open' =>'required',
                'time_tirer' =>'required',



            ]);
            $tirage = DB::table('tirage')->where([
                ['id','=', $request->input('tirage')]
            ])->first();
            if(!$tirage){
                notify()->error('pa gen yon tirage ki rel konsa');
                return back();
            }
            $tirage_record = DB::table('tirage_record')->where([
                ['compagnie_id','=', Session('loginId')],
                ['name','=', $tirage->name]
            ])->first();
            if($tirage_record){
                notify()->error('ou gentan ajouter tiraj sa');
                return back();
            }
            if($request->input('time') > $tirage->hour_tirer){
                notify()->error('le bolet la femen an paka plis ke le li tire a');
                return back();
            }
            $query = DB::table('tirage_record')->insertGetId([
                'compagnie_id' => Session('loginId'),
                'tirage_id' => $tirage->id,
                'name' => $tirage->name,
                'hour' => $request->input('time'), 
                'hour_open' => $request->input('time_open'), 
                'hour_tirer' => $tirage->hour_tirer,       
      
      
                'created_at' => Carbon::now()
            ]);
            notify()->success('tiraj ajoute avek sikse');
            return back();
        } else {
            return view('login');
        }

    }
    public function update(Request $request){
        
        if (Session('loginId')) {
            $validator = $request->validate([
                "time" => "required",
                "time_open" => "required",
                'time_tirer' =>'required',

            ]);
            $tirage_record = DB::table('tirage_record')->where([
                ['compagnie_id','=', Session('loginId')],
                ['id','=', $request->input('id')]
            ])->first();
            if(!$tirage_record){
                notify()->error('yon ere pase');
                return back();
            }
            if(!Empty($request->input('active'))){
                  $active = 1;
            }else{
                  $active =0;
            }
            if($request->input('time') > $request->input('time_tirer')){
                notify()->error('le bolet la femen an paka plis ke le li tire a');
                return back();
            }
            $tirage_record = tirage_record::where('id',$request->input('id'))->update([
                //'hour_tirer'=> $request->input('time_tirer'),
                'hour' => $request->input('time'), 
                'hour_open' => $request->input('time_open'), 

                'is_active' => $active,       
                'updated_at' => Carbon::now()
            ]);
            notify()->success('Modikasyon fet ak sikse');
            return back();
        } else {
            return view('login');
        }

    }
    public function index(){
        if (Session('loginId')) {
            $tirage = tirage_record::where([
            ['compagnie_id','=', Session('loginId')]
            ])->get();

            return view('lister_tirage', ['tirage'=>$tirage]);
        } else {
            return view('login');
        }






    }
    public function getTirage(Request $request){
        if (Session('loginId')) {
            $tirage = DB::table('tirage')->where([
                ['id','=', $request->input('id')]
            ])->first();
            return response()->json($tirage);
        } else {
            return view('login');
        }
    }
}
