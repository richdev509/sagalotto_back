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
    public function store(Request $request){
        
        if (Session('loginId')) {
            $validator = $request->validate([
                "tirage" => "required",
                'time' =>'required',

            ]);
            $tirage = DB::table('tirage')->where([
                ['name','=', $request->input('tirage')]
            ])->first();
            if(!$tirage){
                notify()->error('pa gen yon tirage ki rel konsa');
                return back();
            }
            $tirage_record = DB::table('tirage_record')->where([
                ['compagnie_id','=', Session('loginId')],
                ['name','=', $request->input('tirage')]
            ])->first();
            if($tirage_record){
                notify()->error('ou gentan ajouter tiraj sa');
                return back();
            }
            $query = DB::table('tirage_record')->insertGetId([
                'compagnie_id' => Session('loginId'),
                'tirage_id' => $tirage->id,
                'name' => $request->input('tirage'),
                'hour' => $request->input('time'),       
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
                'hour_tirer'=> $request->input('time_tirer'),
                'hour' => $request->input('time'), 
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
}
