<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\tirage_record;
use Illuminate\Contracts\Session\Session;

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
           ['is_delete','=', 1],
        ])->get();
       
        return view('raportsecond',['bank'=>$bank]);
    }
}
