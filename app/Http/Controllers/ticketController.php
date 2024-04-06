<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ticketController extends Controller
{
    public function index(request $request)
    {

        if (Session('loginId')) {

            if (!empty($request->input('date_debut') && !empty($request->input('date_fin')))) {
                if ($request->input('bank') == 'Tout') {
                    $user = User::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('users.id', 'users.name', 'users.bank_name')
                        ->get();
                    $ticket = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        ['ticket_vendu.is_delete', '=', 0]

                    ]) ->whereDate('ticket_code.created_at', '>=', $request->date_debut)
                        ->whereDate('ticket_code.created_at', '<=', $request->date_fin)
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->join('tirage_record', 'tirage_record.id', '=', 'ticket_vendu.tirage_record_id')
                        ->join('users', 'ticket_code.user_id', 'users.id')
                        ->select('ticket_vendu.*', 'ticket_code.code as ticket_id', 'ticket_code.created_at as date', 'tirage_record.name as tirage', 'ticket_vendu.amount as montant', 'ticket_vendu.winning as gain', 'users.bank_name as bank')
                        ->orderByDesc('id')
                        ->paginate(100);
                    return view('lister-ticket', ['ticket' => $ticket, 'vendeur' => $user]);
                } else {
                    $user = User::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('users.id', 'users.name', 'users.bank_name')
                        ->get();
                    $ticket = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        ['ticket_vendu.is_delete', '=', 0],
                        ['ticket_code.user_id', '=', $request->input('bank')]


                    ]) ->whereDate('ticket_code.created_at', '>=', $request->date_debut)
                        ->whereDate('ticket_code.created_at', '<=', $request->date_fin)
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->join('tirage_record', 'tirage_record.id', '=', 'ticket_vendu.tirage_record_id')
                        ->join('users', 'ticket_code.user_id', 'users.id')
                        ->select('ticket_vendu.*', 'ticket_code.code as ticket_id', 'ticket_code.created_at as date', 'tirage_record.name as tirage', 'ticket_vendu.amount as montant', 'ticket_vendu.winning as gain', 'users.bank_name as bank')
                        ->orderByDesc('id')
                        ->paginate(100);
                    return view('lister-ticket', ['ticket' => $ticket, 'vendeur' => $user]);
                }
            } elseif (!empty($request->input('ticket'))) {

                $user = User::where([
                    ['compagnie_id', '=', Session('loginId')]

                ])->select('users.id', 'users.name', 'users.bank_name')
                    ->get();
                $ticket = DB::table('ticket_code')->where([
                    ['ticket_code.compagnie_id', '=', Session('loginId')],
                    ['ticket_vendu.is_delete', '=', 0],
                    ['ticket_code.code', '=', $request->input('ticket') ]
                ])->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                    ->join('tirage_record', 'tirage_record.id', '=', 'ticket_vendu.tirage_record_id')
                    ->join('users', 'ticket_code.user_id', 'users.id')
                    ->select('ticket_vendu.*', 'ticket_code.code as ticket_id', 'ticket_code.created_at as date', 'tirage_record.name as tirage', 'ticket_vendu.amount as montant', 'ticket_vendu.winning as gain', 'users.bank_name as bank')
                    ->orderByDesc('id')
                    ->paginate(100);
                return view('lister-ticket', ['ticket' => $ticket, 'vendeur' => $user]);
            } else {
                $user = User::where([
                    ['compagnie_id', '=', Session('loginId')]

                ])->select('users.id', 'users.name', 'users.bank_name')
                    ->get();
                $ticket = DB::table('ticket_code')->where([
                    ['ticket_code.compagnie_id', '=', Session('loginId')],
                    ['ticket_vendu.is_delete', '=', 0]

                ])->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                    ->join('tirage_record', 'tirage_record.id', '=', 'ticket_vendu.tirage_record_id')
                    ->join('users', 'ticket_code.user_id', 'users.id')
                    ->select('ticket_vendu.*', 'ticket_code.code as ticket_id', 'ticket_code.created_at as date', 'tirage_record.name as tirage', 'ticket_vendu.amount as montant', 'ticket_vendu.winning as gain', 'users.bank_name as bank')
                    ->orderByDesc('id')
                    ->paginate(100);
                return view('lister-ticket', ['ticket' => $ticket, 'vendeur' => $user]);
            }

            $user = User::where([
                ['compagnie_id', '=', Session('loginId')]

            ])->select('users.id', 'users.name', 'users.bank_name')
                ->get();
            $ticket = DB::table('ticket_code')->where([
                ['ticket_code.compagnie_id', '=', Session('loginId')],
                ['ticket_vendu.is_delete', '=', 0]

            ])->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                ->join('tirage_record', 'tirage_record.id', '=', 'ticket_vendu.tirage_record_id')
                ->join('users', 'ticket_code.user_id', 'users.id')
                ->select('ticket_vendu.*', 'ticket_code.code as ticket_id', 'ticket_code.created_at as date', 'tirage_record.name as tirage', 'ticket_vendu.amount as montant', 'ticket_vendu.winning as gain', 'users.bank_name as bank')
                ->orderByDesc('id')
                ->paginate(100);
            return view('lister-ticket', ['ticket' => $ticket, 'vendeur' => $user]);
        } else {
            return view('login');
        }
    }

    public function show_boule(Request $request)
    {
        if (Session('loginId')) {
            $boule = DB::table('ticket_vendu')->where([
                ['is_delete', '=', 0],
                ['is_cancel', '=', 0],
                ['id', '=', $request->input('id')]
            ])->select('ticket_vendu.boule')
                ->first();
            if ($boule) {
                return response()->json([
                    'status' => 'true',
                    'boule' => $boule
                ]);
            } else {
                return response()->json([
                    'status' => 'false',
                    'boule' => []
                ]);
            }
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'Ou pa konekte'

            ]);
        }
    }
}
