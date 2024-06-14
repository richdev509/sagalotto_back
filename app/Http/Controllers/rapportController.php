<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\tirage_record;
use Illuminate\Contracts\Session\Session;
use Carbon\Carbon;
class rapportController extends Controller
{
    public function create_rapport(Request $request)
    {
        if (Session('loginId')) {
            if (!empty($request->input('date_debut') && !empty($request->input('date_fin')))) {
                if ($request->input('bank') == 'Tout' && $request->input('tirage') == 'Tout') {
                    $vente = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        // ['ticket_code.user_id', '=', auth()->user()->id],
                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.is_delete', '=', 0],

                    ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                        ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->sum('amount');



                    $perte = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        //['ticket_code.user_id', '=', auth()->user()->id],
                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.is_delete', '=', 0],

                    ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                        ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->sum('winning');

                    $commission = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        //['ticket_code.user_id', '=', auth()->user()->id],
                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.is_delete', '=', 0],

                    ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                        ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->sum('commission');


                    $ticket_win = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        // ['ticket_code.user_id', '=', auth()->user()->id],
                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.is_delete', '=', 0],
                        ['ticket_vendu.is_win', '=', 1],


                    ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                        ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->get()->count();

                    $ticket_lose = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        // ['ticket_code.user_id', '=', auth()->user()->id],
                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.is_delete', '=', 0],
                        ['ticket_vendu.is_win', '=', 0],


                    ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                        ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->get()->count();



                    //get vendeur
                    $user = User::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('users.id', 'users.name', 'users.bank_name')
                        ->get();
                    //get tirage
                    $tirage = tirage_record::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('tirage_record.id', 'tirage_record.name')
                        ->get();
                    return view('rapport', ['vente' => $vente, 'perte' => $perte, 'ticket_win' => $ticket_win, 'ticket_lose' => $ticket_lose, 'date_debut' => $request->input('date_debut'), 'date_fin' => $request->input('date_fin'), 'bank' => 'Tout', 'tirage_' => 'Tout', 'is_calculated' => 1, 'vendeur' => $user, 'tirage' => $tirage, 'commission' => $commission]);
                } elseif ($request->input('bank') == 'Tout' && $request->input('tirage') != 'Tout') {
                    $vente = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        ['ticket_vendu.tirage_record_id', '=', $request->input('tirage')],
                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.is_delete', '=', 0],

                    ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                        ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->sum('amount');



                    $perte = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        ['ticket_vendu.tirage_record_id', '=', $request->input('tirage')],
                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.is_delete', '=', 0],

                    ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                        ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->sum('winning');

                    $commission = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        ['ticket_vendu.tirage_record_id', '=', $request->input('tirage')],

                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.is_delete', '=', 0],

                    ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                        ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->sum('commission');


                    $ticket_win = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        ['ticket_vendu.tirage_record_id', '=', $request->input('tirage')],

                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.is_delete', '=', 0],
                        ['ticket_vendu.is_win', '=', 1],


                    ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                        ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->get()->count();

                    $ticket_lose = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        ['ticket_vendu.tirage_record_id', '=', $request->input('tirage')],

                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.is_delete', '=', 0],
                        ['ticket_vendu.is_win', '=', 0],


                    ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                        ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->get()->count();

                    //get vendeur
                    $user = User::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('users.id', 'users.name', 'users.bank_name')
                        ->get();
                    //get tirage
                    $tirage = tirage_record::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('tirage_record.id', 'tirage_record.name')
                        ->get();
                    $name_tirage = tirage_record::where(
                        [
                            ['id', '=', $request->input('tirage')]
                        ]
                    )->select('name')
                        ->first();
                    return view('rapport', ['vente' => $vente, 'perte' => $perte, 'ticket_win' => $ticket_win, 'ticket_lose' => $ticket_lose, 'date_debut' => $request->input('date_debut'), 'date_fin' => $request->input('date_fin'), 'bank' => 'Tout', 'tirage_' => $name_tirage->name, 'is_calculated' => 1, 'vendeur' => $user, 'tirage' => $tirage, 'commission' => $commission]);
                } elseif ($request->input('bank') != 'Tout' && $request->input('tirage') == 'Tout') {
                    $vente = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        ['ticket_code.user_id', '=', $request->input('bank')],
                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.is_delete', '=', 0],

                    ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                        ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->sum('amount');



                    $perte = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        ['ticket_code.user_id', '=', $request->input('bank')],

                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.is_delete', '=', 0],

                    ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                        ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->sum('winning');

                    $commission = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        ['ticket_code.user_id', '=', $request->input('bank')],

                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.is_delete', '=', 0],

                    ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                        ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->sum('commission');


                    $ticket_win = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        ['ticket_code.user_id', '=', $request->input('bank')],

                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.is_delete', '=', 0],
                        ['ticket_vendu.is_win', '=', 1],


                    ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                        ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->get()->count();

                    $ticket_lose = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        ['ticket_code.user_id', '=', $request->input('bank')],

                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.is_delete', '=', 0],
                        ['ticket_vendu.is_win', '=', 0],


                    ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                        ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->get()->count();

                    //get vendeur
                    $user = User::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('users.id', 'users.name', 'users.bank_name')
                        ->get();
                    //get tirage
                    $tirage = tirage_record::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('tirage_record.id', 'tirage_record.name')
                        ->get();
                    $name_bank = User::where([
                        ['id', '=', $request->input('bank')]
                    ])->select('bank_name')
                        ->first();
                    return view('rapport', ['vente' => $vente, 'perte' => $perte, 'ticket_win' => $ticket_win, 'ticket_lose' => $ticket_lose, 'date_debut' => $request->input('date_debut'), 'date_fin' => $request->input('date_fin'), 'bank' => $name_bank->bank_name, 'tirage_' => 'Tout', 'is_calculated' => 1, 'vendeur' => $user, 'tirage' => $tirage, 'commission' => $commission]);
                } elseif ($request->input('bank') != 'Tout' && $request->input('tirage') != 'Tout') {
                    $vente = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        ['ticket_code.user_id', '=', $request->input('bank')],
                        ['ticket_vendu.tirage_record_id', '=', $request->input('tirage')],

                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.is_delete', '=', 0],

                    ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                        ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->sum('amount');



                    $perte = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        ['ticket_code.user_id', '=', $request->input('bank')],
                        ['ticket_vendu.tirage_record_id', '=', $request->input('tirage')],

                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.is_delete', '=', 0],

                    ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                        ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->sum('winning');

                    $commission = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        ['ticket_code.user_id', '=', $request->input('bank')],
                        ['ticket_vendu.tirage_record_id', '=', $request->input('tirage')],

                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.is_delete', '=', 0],

                    ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                        ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->sum('commission');


                    $ticket_win = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        ['ticket_code.user_id', '=', $request->input('bank')],
                        ['ticket_vendu.tirage_record_id', '=', $request->input('tirage')],

                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.is_delete', '=', 0],
                        ['ticket_vendu.is_win', '=', 1],


                    ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                        ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->get()->count();

                    $ticket_lose = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        ['ticket_code.user_id', '=', $request->input('bank')],
                        ['ticket_vendu.tirage_record_id', '=', $request->input('tirage')],

                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.is_delete', '=', 0],
                        ['ticket_vendu.is_win', '=', 0],


                    ])->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                        ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->get()->count();

                    //get vendeur
                    $user = User::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('users.id', 'users.name', 'users.bank_name')
                        ->get();
                    //get tirage
                    $tirage = tirage_record::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('tirage_record.id', 'tirage_record.name')
                        ->get();
                    $name_tirage = tirage_record::where(
                        [
                            ['id', '=', $request->input('tirage')]
                        ]
                    )->select('name')
                        ->first();
                    $name_bank = User::where([
                        ['id', '=', $request->input('bank')]
                    ])->select('bank_name')
                        ->first();
                    return view('rapport', ['vente' => $vente, 'perte' => $perte, 'ticket_win' => $ticket_win, 'ticket_lose' => $ticket_lose, 'date_debut' => $request->input('date_debut'), 'date_fin' => $request->input('date_fin'), 'bank' => $name_bank->bank_name, 'tirage_' => $name_tirage->name, 'is_calculated' => 1, 'vendeur' => $user, 'tirage' => $tirage, 'commission' => $commission]);
                }
            } else {
                //get vendeur
                $user = User::where([
                    ['compagnie_id', '=', Session('loginId')]

                ])->select('users.id', 'users.name', 'users.bank_name')
                    ->get();
                //get tirage
                $tirage = tirage_record::where([
                    ['compagnie_id', '=', Session('loginId')]

                ])->select('tirage_record.id', 'tirage_record.name')
                    ->get();
                return view('rapport', ['vendeur' => $user, 'tirage' => $tirage, 'is_calculated' => 0]);
            }
        } else {
            return view('login');
        }
    }
    public function create_rapport2(Request $request){
        $bank = User::where([
           ['compagnie_id','=', Session('loginId')],
           ['is_delete','=', 0],
        ])->get();
        //control historique
        $control = DB::table('tbl_control')->where([
            ['tbl_control.compagnie_id','=', Session('loginId')],
         ])->join('users', 'users.code','=','tbl_control.id_user')
         ->select('tbl_control.*','users.bank_name')
         ->orderByDesc('date_rapport')
         ->limit('50')
         ->get();
        return view('raportsecond',['bank'=>$bank, 'control'=>$control]);
    }
    public function get_control(Request $request){
        $user_id = $request->input('user');
        $date = $request->input('date');

        $user = User::where([
           ['id','=', $user_id]
        ])->first();
        $control = DB::table('tbl_control')->where([
            ['id_user','=', $user_id],
            ['compagnie_id','=', Session('loginId')]
        ])->whereDate('date_rapport','=', $date)->first();
        //get the control if it exist
        if($control){
            return response()->json([
                'control'=>1,
                'status'=>'true',
                'bank_code'=>$user->code,
                'bank'=>$user->bank_name,
                'montant'=>$control->montant,
                'balance'=>$control->balance,
                'date'=> $date
  
            ]);
        }
        $vente = DB::table('ticket_code')->where([
            ['ticket_code.compagnie_id', '=', Session('loginId')],
            ['ticket_code.user_id', '=', $user_id],
            ['ticket_vendu.is_cancel', '=', 0],
            ['ticket_vendu.is_delete', '=', 0],

        ])->whereDate('ticket_code.created_at', '=', $date)
            ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
            ->sum('amount');



        $perte = DB::table('ticket_code')->where([
            ['ticket_code.compagnie_id', '=', Session('loginId')],
            ['ticket_code.user_id', '=', $user_id],

            ['ticket_vendu.is_cancel', '=', 0],
            ['ticket_vendu.is_delete', '=', 0],

        ])->whereDate('ticket_code.created_at', '=', $date)
            ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
            ->sum('winning');

        $commission = DB::table('ticket_code')->where([
            ['ticket_code.compagnie_id', '=', Session('loginId')],
            ['ticket_code.user_id', '=', $user_id],

            ['ticket_vendu.is_cancel', '=', 0],
            ['ticket_vendu.is_delete', '=', 0],

        ])->whereDate('ticket_code.created_at', '=', $date)
            ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
            ->sum('commission');
         $montant = $vente -($perte + $commission);

          return response()->json([
              'status'=>'true',
              'control'=>0,
              'bank_code'=>$user->code,
              'bank'=>$user->bank_name,
              'montant'=>$montant,
              'date'=> $date

          ]);



    }
    public function save_control(Request $request){
        $validator = $request->validate([
            "vendeur" => "required",
            'ddate' =>'required',
            'amount' =>'required',
            'amount_' =>'required',
        ]);
        //get user id
        if($request->input('amount_')> $request->input('amount')){
            return response()->json([
                'save'=>0,
                'status'=>'false',
                'message'=> 'montan pa dwe depase montan rapo a'
            ]);
        }
        $control = DB::table('tbl_control')->where([
            ['compagnie_id','=', Session('loginId')],
            ['date_rapport','=', $request->input('ddate')],
            ['id_user','=', $request->input('vendeur')]
        ])->first();
        if($control){
            return response()->json([
                'save'=>0,
                'status'=>'false',
                'message'=> 'rapo sa anregistre deja'
            ]);
        }else{
            $query = DB::table('tbl_control')->insertGetId([
                'compagnie_id' => Session('loginId'),
                'date_rapport'=> $request->input('ddate'),
                'id_user'=> $request->input('vendeur'),
                'montant'=> $request->input('amount_'),
                'balance'=> $request->input('amount') - $request->input('amount_'),
                'created_at' => Carbon::now()
            ]);
            return response()->json([
                'save'=>1,
                'status'=>'true',
                'message'=> 'anregistrement fet ak sikse'
            ]);

        }

    }
}
