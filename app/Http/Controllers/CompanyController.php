<?php

namespace App\Http\Controllers;

use App\Models\company;
use App\Models\user;
use App\Http\Requests\StorecompanyRequest;
use App\Http\Requests\UpdatecompanyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index_vendeur()
    {
        return view('lister_vendeur');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorecompanyRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatecompanyRequest $request, company $company)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(company $company)
    {
        //
    }
    public function login(Request $request)
    {
        $username =  $_POST['username'];
        $password =  $_POST['password'];

        if (empty($username) || empty($password)) {
            notify()->error('ranpli tout chan yo');
            return redirect('/login');
        } else {

            $user = company::where([
                ['username', '=', $username],

            ])->first();
            //try to know if user
            if ($user) {
                //User found let tcheck the password
                if (Hash::check($password, $user->password)) {
                    //Password match let find if user not block
                    if ($user->is_block != '1') {
                        $request->session()->put('loginId', $user->id);
                        $request->session()->put('name', $user->name);
                        $request->session()->put('logo', $user->logo);
                        notify()->success('Bienvenue  ' . $user->name);
                        return view('admin');
                    } else {
                        notify()->error('kont ou bloke kontakte sagacelotto');
                        return redirect('/login');
                    }
                } else {
                    notify()->error('modepass la pa bon');

                    return redirect('/login');
                }
            } else {
                notify()->error('ranpli tout chan yo');

                return redirect('/login')->with('error', 'Utilisateur non trouve');
            }
        }
    }
    public function admin()
    {
        if (Session('loginId')) {
            return view('admin');
        } else {
            return view('login');
        }
    }
    public function logout()
    {
        if (Session('loginId')) {
            Session()->forget('loginId');
            Session()->forget('name');
            Session()->forget('logo');
            return view('login');
        } else {
            return view('login');
        }
    }
    public function create_vendeur()
    {
        if (Session('loginId')) {
            return view('ajouter_vendeur');
        } else {
            return view('login');
        }
    }

    public function store_vendeur(Request $request)
    {
        if (Session('loginId')) {
    
            $validator = $request->validate([
                "name" => "required|max:50",
                'bank_id' =>'required',
                'phone'=>'required|numeric',
                'percent'=>'required|max:100|numeric',
                "bank_name" => "required|max:30",
                "password" => "required|max:20",
                "username" => "required|max:20|unique:users"

            ]);
            $vendeur = user::where([
                ['compagnie_id','=', Session('loginId')],
                ['bank_name','=', $request->bank_name]
            ])->first();
            if($vendeur){
                notify()->error('ou gen yon bank ak non sa deja');
                return back();
            }

            if($request->input('block') =='1'){
                 $status = 1;

            }else{
                $status = 0;
            }
            $query = DB::table('users')->insertGetId([
                'compagnie_id' => Session('loginId'),
                'name' => $request->input('name'),
                'address' => $request->input('address'),
                'gender' => $request->input('gender'),   
                'phone' => $request->input('phone'),
                'username' => $request->input('username'),  
                'percent' => $request->input('percent'),
                'android_id' => $request->input('bank_id'),
                'bank_name' => $request->input('bank_name'),
                'password' => Hash::make($request->input('bank_name')),
                'is_block' => $status,
                'created_at' => Carbon::now()
            ]);
             $user = user::find($query);
             $user->code = "V-00".$query;
             $user->save();
             notify()->success('Vandè a anregistre avec siksè');
             return back();

        } else {
            return view('login');
        }
    }

    public function profil(){
        $user = user::find(1);
        $user->password = Hash::make('123456');
        $user->save();
    }
}
