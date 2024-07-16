<?php

namespace App\Http\Controllers;

use App\Models\company;
use App\Models\User;
use App\Http\Requests\StorecompanyRequest;
use App\Http\Requests\UpdatecompanyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\BoulGagnant;
use App\Models\branch;
use App\Models\TicketVendu;
use App\Models\ticket_code;

//use Carbon\Carbon;
class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index_vendeur()
    {
        if (Session('loginId')) {
            $vendeur = user::where([
                ['compagnie_id', '=', Session('loginId')],
                ['is_delete', '=', 0]
            ])->get();
            return view('lister_vendeur', ['vendeur' => $vendeur]);
        } else {
            return view('login');
        }
    }

    public function edit_vendeur(Request $request)
    {
        if (Session('loginId')) {
            $vendeur = user::where([
                ['compagnie_id', '=', Session('loginId')],
                ['id', '=', $request->input('id')],
                ['is_delete', '=', 0]
            ])->first();
            if (!$vendeur) {
                notify()->error('Gen yon ere ki pase');
                return back();
            }
            return view('editer_vendeur', ['vendeur' => $vendeur]);
        } else {
            return view('login');
        }
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
        $username =  $request->input('username');
        $password =  $request->input('password');

        if (empty($username) || empty($password)) {
            notify()->error('ranpli tout chan yo');
            return back();
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
                        return redirect('/admin');
                    } else {
                        notify()->error('kont ou bloke kontakte sagacelotto');
                        return redirect('/login');
                    }
                } else {
                    notify()->error('modepass la pa bon');

                    return redirect('/login');
                }
            } else {
                notify()->error('non itilizate a inkorek');

                return redirect('/login')->with('error', 'Utilisateur non trouve');
            }
        }
    }
    public function admin()
    {
        if (Session('loginId')) {

            $vente = DB::table('ticket_code')->where([
                ['compagnie_id', '=', Session('loginId')],
                ['ticket_vendu.is_delete', '=', 0],
                ['ticket_vendu.is_cancel', '=', 0],
            ])->whereDate('ticket_code.created_at', '=', Carbon::now())
                ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                ->sum('amount');

            $perte = DB::table('ticket_code')->where([
                ['compagnie_id', '=', Session('loginId')],
                ['ticket_vendu.is_delete', '=', 0],
                ['ticket_vendu.is_cancel', '=', 0],
            ])->whereDate('ticket_code.created_at', '=', Carbon::now())
                ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                ->sum('winning');

            $commission = DB::table('ticket_code')->where([
                ['compagnie_id', '=', Session('loginId')],
                ['ticket_vendu.is_delete', '=', 0],
                ['ticket_vendu.is_cancel', '=', 0],
            ])->whereDate('ticket_code.created_at', '=', Carbon::now())
                ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                ->sum('commission');

         

            $lista = BoulGagnant::where('compagnie_id', session('loginId'))
                ->latest('created_at')
                ->take(3)
                ->get();
           
            $list = [];
           

            foreach ($lista as $boulGagnant) {
                $codes = ticket_code::where('compagnie_id', session('loginId'))
                ->whereDate('created_at', $boulGagnant->created_)
                ->pluck('code')
                ->toArray();

             $vent= TicketVendu::whereIn('ticket_code_id', $codes)
             ->where('tirage_record_id', $boulGagnant->tirage_id)->where('is_delete',0)->where('is_cancel',0)
             ->sum('amount');
               /* $vente =TicketVendu::where('tirage_record_id',$boulGagnant->tirage_id)
                    ->whereDate('created_at', $boulGagnant->created_)
                    ->sum('amount');
                    dd($vente,);*/
                $pert =TicketVendu::whereIn('ticket_code_id', $codes)
                ->where('tirage_record_id', $boulGagnant->tirage_id)->where('is_delete',0)->where('is_cancel',0)
                    ->sum('winning');

                $commissio =TicketVendu::whereIn('ticket_code_id', $codes)
             ->where('tirage_record_id', $boulGagnant->tirage_id)->where('is_delete',0)->where('is_cancel',0)
                    ->sum('commission');
                $tirageName = $boulGagnant->tirage_record->name;
                $list[] = [
                    'boulGagnant' => $boulGagnant,
                    'vent' => $vent,
                    'pert' => $pert,
                    'commissio' => $commissio,
                    'name'=>$tirageName


                ];
            }


            return view('admin', ['vente' => $vente, 'perte' => $perte, 'list' => $list, 'commission' => $commission]);
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
            $branch = branch::where([
               ['compagnie_id','=', Session('loginId')]
            ])->get();
            return view('ajouter_vendeur',['branch'=>$branch]);
        } else {
            return view('login');
        }
    }

    public function store_vendeur(Request $request)
    {
        if (Session('loginId')) {

            $validator = $request->validate([
                "name" => "required|max:50",
                'bank_id' => 'required',
                'phone' => 'required|numeric',
                'percent' => 'required|max:100|numeric',
                'branch' => 'required',

                "bank_name" => "required|max:30",
                "password" => "required|max:20",
                "username" => "required|max:20|unique:users"

            ]);
            $vendeur = user::where([
                ['compagnie_id', '=', Session('loginId')],
                ['bank_name', '=', $request->bank_name]
            ])->first();
            if ($vendeur) {
                notify()->error('ou gen yon bank ak non sa deja');
                return back();
            }

            if ($request->input('block') == '1') {
                $status = 1;
            } else {
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
                'branch_id' => $request->input('branch'),

                'android_id' => $request->input('bank_id'),
                'bank_name' => $request->input('bank_name'),
                'password' => Hash::make($request->input('password')),
                'is_block' => $status,
                'created_at' => Carbon::now()
            ]);
            $user = user::find($query);
            $user->code = "V-00" . $query;
            $user->save();
            notify()->success('Vandè a anregistre avec siksè');
            return back();
        } else {
            return view('login');
        }
    }

    public function update_vendeur(Request $request)
    {

        if (Session('loginId')) {

            $validator = $request->validate([
                "name" => "required|max:50",
                'bank_id' => 'required',
                'phone' => 'required|numeric',
                'percent' => 'required|max:100|numeric',
                "bank_name" => "required|max:30",



            ]);
            $vendeur = user::where([
                ['compagnie_id', '=', Session('loginId')],
                ['id', '=', $request->input('id')]
            ])->first();
            if (!$vendeur) {
                notify()->error('vande sa pa trouve');
                return back();
            }

            if ($request->input('block') == '1') {
                $status = 1;
            } else {
                $status = 0;
            }
            if (!empty($request->input('password'))) {
                $user = user::where('id', $request->input('id'))->update([
                    'name' => $request->input('name'),
                    'address' => $request->input('address'),
                    'gender' => $request->input('gender'),
                    'phone' => $request->input('phone'),
                    //'username' => $request->input('username'),  
                    'percent' => $request->input('percent'),
                    'android_id' => $request->input('bank_id'),
                    'bank_name' => $request->input('bank_name'),
                    'password' => Hash::make($request->input('password')),
                    'is_block' => $status,
                    'created_at' => Carbon::now()
                ]);

                notify()->success('modifikasyon an fet avek siksè');
                return back();
            } else {
                $user = user::where('id', $request->input('id'))->update([
                    'name' => $request->input('name'),
                    'address' => $request->input('address'),
                    'gender' => $request->input('gender'),
                    'phone' => $request->input('phone'),
                    //'username' => $request->input('username'),  
                    'percent' => $request->input('percent'),
                    'android_id' => $request->input('bank_id'),
                    'bank_name' => $request->input('bank_name'),
                    //'password' => Hash::make($request->input('bank_name')),
                    'is_block' => $status,
                    'created_at' => Carbon::now()
                ]);

                notify()->success('modifikasyon an fet avek siksè');
                return back();
            }
        } else {
            return view('login');
        }
    }

    //compagnie
    public function new_password(Request $request)
    {
        if (!empty(Session('loginId'))) {
            $validateData = $request->validate([
                'old_password' => 'required',
                'password' => 'required|confirmed|max:20|min:8',

            ]);
            if ($validateData) {
                $user = Company::where([
                    ['id', '=', Session('loginId')],
                ])->first();
                if (Hash::check($request->input('old_password'), $user->password)) {
                    $user->password = Hash::make($request->input('password'));
                    $user->save();
                    notify()->success('mo de pass ou a chanje');
                    return back();
                } else {
                    notify()->error('ansyen modepass la pa bon');
                    return back();
                }
            } else {
                notify()->error('yon ere pase');
                return back();
            }
        } else {

            return view('login');
        }
    }
}
