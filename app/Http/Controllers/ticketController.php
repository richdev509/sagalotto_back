<?php

namespace App\Http\Controllers;

use App\Models\TicketVendu;
use App\Models\tirage_record;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ticketController extends Controller
{
    public function index(request $request)
    {

        if (Session('loginId')) {

            if (!empty($request->input('date_debut') && !empty($request->input('date_fin')))) {
                if ($request->input('bank') == 'Tout' && $request->input('tirage') == 'Tout') {
                    //get bank
                    $user = User::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('users.id', 'users.name', 'users.bank_name')
                        ->get();
                    //get tirage
                    $tirage = tirage_record::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('id', 'name')
                        ->get();
                    $ticket = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        ['ticket_vendu.is_delete', '=', 0],
                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.pending', '=', 0],



                    ])->whereDate('ticket_code.created_at', '>=', $request->date_debut)
                        ->whereDate('ticket_code.created_at', '<=', $request->date_fin)
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->join('tirage_record', 'tirage_record.id', '=', 'ticket_vendu.tirage_record_id')
                        ->join('users', 'ticket_code.user_id', 'users.id')
                        ->select('ticket_vendu.*', 'ticket_code.code as ticket_id', 'ticket_code.created_at as date', 'tirage_record.name as tirage', 'ticket_vendu.amount as montant', 'ticket_vendu.winning as gain', 'users.bank_name as bank')
                        ->orderByDesc('id')
                        ->paginate(100);
                    $ticket->appends(['date_debut' => $request->input('date_debut'), 'date_fin' => $request->input('date_fin'), 'bank' => 'Tout', 'tirage' => 'Tout']);
                    return view('lister-ticket', ['ticket' => $ticket, 'vendeur' => $user, 'tirage' => $tirage]);
                } elseif ($request->input('bank') != 'Tout' && $request->input('tirage') == 'Tout') {
                    //get bank
                    $user = User::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('users.id', 'users.name', 'users.bank_name')
                        ->get();
                    //get tirage
                    $tirage = tirage_record::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('id', 'name')
                        ->get();
                    $ticket = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        ['ticket_vendu.is_delete', '=', 0],
                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.pending', '=', 0],

                        ['ticket_code.user_id', '=', $request->input('bank')],


                    ])->whereDate('ticket_code.created_at', '>=', $request->date_debut)
                        ->whereDate('ticket_code.created_at', '<=', $request->date_fin)
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->join('tirage_record', 'tirage_record.id', '=', 'ticket_vendu.tirage_record_id')
                        ->join('users', 'ticket_code.user_id', 'users.id')
                        ->select('ticket_vendu.*', 'ticket_code.code as ticket_id', 'ticket_code.created_at as date', 'tirage_record.name as tirage', 'ticket_vendu.amount as montant', 'ticket_vendu.winning as gain', 'users.bank_name as bank')
                        ->orderByDesc('id')
                        ->paginate(100);
                    $ticket->appends(['date_debut' => $request->input('date_debut'), 'date_fin' => $request->input('date_fin'), 'bank' => $request->input('bank'), 'tirage' => 'Tout']);
                    return view('lister-ticket', ['ticket' => $ticket, 'vendeur' => $user, 'tirage' => $tirage]);
                } elseif ($request->input('bank') != 'Tout' && $request->input('tirage') != 'Tout') {
                    //get bank
                    $user = User::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('users.id', 'users.name', 'users.bank_name')
                        ->get();
                    //get tirage
                    $tirage = tirage_record::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('id', 'name')
                        ->get();
                    $ticket = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        ['ticket_vendu.is_delete', '=', 0],
                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.pending', '=', 0],

                        ['ticket_code.user_id', '=', $request->input('bank')],
                        ['ticket_vendu.tirage_record_id', '=', $request->input('tirage')],



                    ])->whereDate('ticket_code.created_at', '>=', $request->date_debut)
                        ->whereDate('ticket_code.created_at', '<=', $request->date_fin)
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')

                        ->join('tirage_record', 'tirage_record.id', '=', 'ticket_vendu.tirage_record_id')
                        ->join('users', 'ticket_code.user_id', 'users.id')
                        ->select('ticket_vendu.*', 'ticket_code.code as ticket_id', 'ticket_code.created_at as date', 'tirage_record.name as tirage', 'ticket_vendu.amount as montant', 'ticket_vendu.winning as gain', 'users.bank_name as bank')
                        ->orderByDesc('id')
                        ->paginate(100);
                    $ticket->appends(['date_debut' => $request->input('date_debut'), 'date_fin' => $request->input('date_fin'), 'bank' => $request->input('bank'), 'tirage' => $request->input('tirage')]);
                    return view('lister-ticket', ['ticket' => $ticket, 'vendeur' => $user, 'tirage' => $tirage]);
                } elseif ($request->input('bank') == 'Tout' && $request->input('tirage') != 'Tout') {
                    //get bank
                    $user = User::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('users.id', 'users.name', 'users.bank_name')
                        ->get();
                    //get tirage
                    $tirage = tirage_record::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('id', 'name')
                        ->get();
                    $ticket = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        ['ticket_vendu.is_delete', '=', 0],
                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.pending', '=', 0],

                        ['ticket_vendu.tirage_record_id', '=', $request->input('tirage')],



                    ])->whereDate('ticket_code.created_at', '>=', $request->date_debut)
                        ->whereDate('ticket_code.created_at', '<=', $request->date_fin)
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')

                        ->join('tirage_record', 'tirage_record.id', '=', 'ticket_vendu.tirage_record_id')
                        ->join('users', 'ticket_code.user_id', 'users.id')
                        ->select('ticket_vendu.*', 'ticket_code.code as ticket_id', 'ticket_code.created_at as date', 'tirage_record.name as tirage', 'ticket_vendu.amount as montant', 'ticket_vendu.winning as gain', 'users.bank_name as bank')
                        ->orderByDesc('id')
                        ->paginate(100);
                    $ticket->appends(['date_debut' => $request->input('date_debut'), 'date_fin' => $request->input('date_fin'), 'bank' => 'Tout', 'tirage' => $request->input('tirage')]);

                    return view('lister-ticket', ['ticket' => $ticket, 'vendeur' => $user, 'tirage' => $tirage]);
                } else {
                    $user = User::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('users.id', 'users.name', 'users.bank_name')
                        ->get();

                    $tirage = tirage_record::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('id', 'name')
                        ->get();
                    $ticket = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        ['ticket_vendu.is_delete', '=', 0],
                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.pending', '=', 0],

                        ['ticket_code.user_id', '=', $request->input('bank')]


                    ])->whereDate('ticket_code.created_at', '>=', $request->date_debut)
                        ->whereDate('ticket_code.created_at', '<=', $request->date_fin)
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->join('tirage_record', 'tirage_record.id', '=', 'ticket_vendu.tirage_record_id')
                        ->join('users', 'ticket_code.user_id', 'users.id')
                        ->select('ticket_vendu.*', 'ticket_code.code as ticket_id', 'ticket_code.created_at as date', 'tirage_record.name as tirage', 'ticket_vendu.amount as montant', 'ticket_vendu.winning as gain', 'users.bank_name as bank')
                        ->orderByDesc('id')
                        ->paginate(100);
                    return view('lister-ticket', ['ticket' => $ticket, 'vendeur' => $user, 'tirage' => $tirage]);
                }
            } elseif (!empty($request->input('ticket'))) {

                $user = User::where([
                    ['compagnie_id', '=', Session('loginId')]

                ])->select('users.id', 'users.name', 'users.bank_name')
                    ->get();
                $tirage = tirage_record::where([
                    ['compagnie_id', '=', Session('loginId')]

                ])->select('id', 'name')
                    ->get();

                $ticket = DB::table('ticket_code')->where([
                    ['ticket_code.compagnie_id', '=', Session('loginId')],
                    ['ticket_vendu.is_delete', '=', 0],
                    ['ticket_code.code', '=', $request->input('ticket')],
                    ['ticket_vendu.is_cancel', '=', 0],
                    ['ticket_vendu.pending', '=', 0],


                ])->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                    ->join('tirage_record', 'tirage_record.id', '=', 'ticket_vendu.tirage_record_id')
                    ->join('users', 'ticket_code.user_id', 'users.id')
                    ->select('ticket_vendu.*', 'ticket_code.code as ticket_id', 'ticket_code.created_at as date', 'tirage_record.name as tirage', 'ticket_vendu.amount as montant', 'ticket_vendu.winning as gain', 'users.bank_name as bank')
                    ->orderByDesc('id')
                    ->paginate(100);
                return view('lister-ticket', ['ticket' => $ticket, 'vendeur' => $user, 'tirage' => $tirage]);
            } else {
                $user = User::where([
                    ['compagnie_id', '=', Session('loginId')]

                ])->select('users.id', 'users.name', 'users.bank_name')
                    ->get();

                $tirage = tirage_record::where([
                    ['compagnie_id', '=', Session('loginId')]

                ])->select('id', 'name')
                    ->get();
                $formattedDate = now()->toDateString();
                $formattedDate = now()->toDateString() . ' 00:00:00';
                $formattedDate1 = now()->toDateString() . ' 23:59:00';



                $ticket = DB::table('ticket_code')
                    ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                    ->join('tirage_record', 'tirage_record.id', '=', 'ticket_vendu.tirage_record_id')
                    ->join('users', 'ticket_code.user_id', '=', 'users.id')
                    ->whereBetween('ticket_code.created_at', [$formattedDate, $formattedDate1])
                    ->where('ticket_code.compagnie_id', Session('loginId'))
                    ->where('ticket_vendu.is_delete', 0)
                    ->where('ticket_vendu.is_cancel', 0)
                    ->select('ticket_vendu.*', 'ticket_code.code as ticket_id', 'ticket_code.created_at as date', 'tirage_record.name as tirage', 'ticket_vendu.amount as montant', 'ticket_vendu.winning as gain', 'users.bank_name as bank')
                    ->orderByDesc('ticket_vendu.id')
                    ->simplePaginate(50);

                return view('lister-ticket', ['ticket' => $ticket, 'vendeur' => $user, 'tirage' => $tirage]);
            }
        } else {
            return view('login');
        }
    }
    public function index_delete(request $request)
    {

        if (Session('loginId')) {

            if (!empty($request->input('date_debut') && !empty($request->input('date_fin')))) {
                if ($request->input('bank') == 'Tout' && $request->input('tirage') == 'Tout') {
                    //get bank
                    $user = User::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('users.id', 'users.name', 'users.bank_name')
                        ->get();
                    //get tirage
                    $tirage = tirage_record::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('id', 'name')
                        ->get();
                    $ticket = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        ['ticket_vendu.is_delete', '=', 0],
                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.pending', '=', 0],



                    ])->whereDate('ticket_code.created_at', '>=', $request->date_debut)
                        ->whereDate('ticket_code.created_at', '<=', $request->date_fin)
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->join('tirage_record', 'tirage_record.id', '=', 'ticket_vendu.tirage_record_id')
                        ->join('users', 'ticket_code.user_id', 'users.id')
                        ->select('ticket_vendu.*', 'ticket_code.code as ticket_id', 'ticket_code.created_at as date', 'tirage_record.name as tirage', 'ticket_vendu.amount as montant', 'ticket_vendu.winning as gain', 'users.bank_name as bank')
                        ->orderByDesc('id')
                        ->paginate(100);
                    $ticket->appends(['date_debut' => $request->input('date_debut'), 'date_fin' => $request->input('date_fin'), 'bank' => 'Tout', 'tirage' => 'Tout']);
                    return view('lister-ticket', ['ticket' => $ticket, 'vendeur' => $user, 'tirage' => $tirage]);
                } elseif ($request->input('bank') != 'Tout' && $request->input('tirage') == 'Tout') {
                    //get bank
                    $user = User::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('users.id', 'users.name', 'users.bank_name')
                        ->get();
                    //get tirage
                    $tirage = tirage_record::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('id', 'name')
                        ->get();
                    $ticket = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        ['ticket_vendu.is_delete', '=', 0],
                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.pending', '=', 0],

                        ['ticket_code.user_id', '=', $request->input('bank')],


                    ])->whereDate('ticket_code.created_at', '>=', $request->date_debut)
                        ->whereDate('ticket_code.created_at', '<=', $request->date_fin)
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->join('tirage_record', 'tirage_record.id', '=', 'ticket_vendu.tirage_record_id')
                        ->join('users', 'ticket_code.user_id', 'users.id')
                        ->select('ticket_vendu.*', 'ticket_code.code as ticket_id', 'ticket_code.created_at as date', 'tirage_record.name as tirage', 'ticket_vendu.amount as montant', 'ticket_vendu.winning as gain', 'users.bank_name as bank')
                        ->orderByDesc('id')
                        ->paginate(100);
                    $ticket->appends(['date_debut' => $request->input('date_debut'), 'date_fin' => $request->input('date_fin'), 'bank' => $request->input('bank'), 'tirage' => 'Tout']);
                    return view('lister-ticket', ['ticket' => $ticket, 'vendeur' => $user, 'tirage' => $tirage]);
                } elseif ($request->input('bank') != 'Tout' && $request->input('tirage') != 'Tout') {
                    //get bank
                    $user = User::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('users.id', 'users.name', 'users.bank_name')
                        ->get();
                    //get tirage
                    $tirage = tirage_record::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('id', 'name')
                        ->get();
                    $ticket = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        ['ticket_vendu.is_delete', '=', 0],
                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.pending', '=', 0],

                        ['ticket_code.user_id', '=', $request->input('bank')],
                        ['ticket_vendu.tirage_record_id', '=', $request->input('tirage')],



                    ])->whereDate('ticket_code.created_at', '>=', $request->date_debut)
                        ->whereDate('ticket_code.created_at', '<=', $request->date_fin)
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')

                        ->join('tirage_record', 'tirage_record.id', '=', 'ticket_vendu.tirage_record_id')
                        ->join('users', 'ticket_code.user_id', 'users.id')
                        ->select('ticket_vendu.*', 'ticket_code.code as ticket_id', 'ticket_code.created_at as date', 'tirage_record.name as tirage', 'ticket_vendu.amount as montant', 'ticket_vendu.winning as gain', 'users.bank_name as bank')
                        ->orderByDesc('id')
                        ->paginate(100);
                    $ticket->appends(['date_debut' => $request->input('date_debut'), 'date_fin' => $request->input('date_fin'), 'bank' => $request->input('bank'), 'tirage' => $request->input('tirage')]);
                    return view('lister-ticket', ['ticket' => $ticket, 'vendeur' => $user, 'tirage' => $tirage]);
                } elseif ($request->input('bank') == 'Tout' && $request->input('tirage') != 'Tout') {
                    //get bank
                    $user = User::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('users.id', 'users.name', 'users.bank_name')
                        ->get();
                    //get tirage
                    $tirage = tirage_record::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('id', 'name')
                        ->get();
                    $ticket = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        ['ticket_vendu.is_delete', '=', 0],
                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.pending', '=', 0],

                        ['ticket_vendu.tirage_record_id', '=', $request->input('tirage')],



                    ])->whereDate('ticket_code.created_at', '>=', $request->date_debut)
                        ->whereDate('ticket_code.created_at', '<=', $request->date_fin)
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')

                        ->join('tirage_record', 'tirage_record.id', '=', 'ticket_vendu.tirage_record_id')
                        ->join('users', 'ticket_code.user_id', 'users.id')
                        ->select('ticket_vendu.*', 'ticket_code.code as ticket_id', 'ticket_code.created_at as date', 'tirage_record.name as tirage', 'ticket_vendu.amount as montant', 'ticket_vendu.winning as gain', 'users.bank_name as bank')
                        ->orderByDesc('id')
                        ->paginate(100);
                    $ticket->appends(['date_debut' => $request->input('date_debut'), 'date_fin' => $request->input('date_fin'), 'bank' => 'Tout', 'tirage' => $request->input('tirage')]);

                    return view('lister-ticket', ['ticket' => $ticket, 'vendeur' => $user, 'tirage' => $tirage]);
                } else {
                    $user = User::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('users.id', 'users.name', 'users.bank_name')
                        ->get();

                    $tirage = tirage_record::where([
                        ['compagnie_id', '=', Session('loginId')]

                    ])->select('id', 'name')
                        ->get();
                    $ticket = DB::table('ticket_code')->where([
                        ['ticket_code.compagnie_id', '=', Session('loginId')],
                        ['ticket_vendu.is_delete', '=', 0],
                        ['ticket_vendu.is_cancel', '=', 0],
                        ['ticket_vendu.pending', '=', 0],

                        ['ticket_code.user_id', '=', $request->input('bank')]


                    ])->whereDate('ticket_code.created_at', '>=', $request->date_debut)
                        ->whereDate('ticket_code.created_at', '<=', $request->date_fin)
                        ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                        ->join('tirage_record', 'tirage_record.id', '=', 'ticket_vendu.tirage_record_id')
                        ->join('users', 'ticket_code.user_id', 'users.id')
                        ->select('ticket_vendu.*', 'ticket_code.code as ticket_id', 'ticket_code.created_at as date', 'tirage_record.name as tirage', 'ticket_vendu.amount as montant', 'ticket_vendu.winning as gain', 'users.bank_name as bank')
                        ->orderByDesc('id')
                        ->paginate(100);
                    return view('lister-ticket', ['ticket' => $ticket, 'vendeur' => $user, 'tirage' => $tirage]);
                }
            } elseif (!empty($request->input('ticket'))) {

                $user = User::where([
                    ['compagnie_id', '=', Session('loginId')]

                ])->select('users.id', 'users.name', 'users.bank_name')
                    ->get();
                $tirage = tirage_record::where([
                    ['compagnie_id', '=', Session('loginId')]

                ])->select('id', 'name')
                    ->get();

                $ticket = DB::table('ticket_code')->where([
                    ['ticket_code.compagnie_id', '=', Session('loginId')],
                    ['ticket_vendu.is_delete', '=', 0],
                    ['ticket_code.code', '=', $request->input('ticket')],
                    ['ticket_vendu.is_cancel', '=', 0],
                    ['ticket_vendu.pending', '=', 0],


                ])->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                    ->join('tirage_record', 'tirage_record.id', '=', 'ticket_vendu.tirage_record_id')
                    ->join('users', 'ticket_code.user_id', 'users.id')
                    ->select('ticket_vendu.*', 'ticket_code.code as ticket_id', 'ticket_code.created_at as date', 'tirage_record.name as tirage', 'ticket_vendu.amount as montant', 'ticket_vendu.winning as gain', 'users.bank_name as bank')
                    ->orderByDesc('id')
                    ->paginate(100);
                return view('lister-ticket', ['ticket' => $ticket, 'vendeur' => $user, 'tirage' => $tirage]);
            } else {
                $user = User::where([
                    ['compagnie_id', '=', Session('loginId')]

                ])->select('users.id', 'users.name', 'users.bank_name')
                    ->get();

                $tirage = tirage_record::where([
                    ['compagnie_id', '=', Session('loginId')]

                ])->select('id', 'name')
                    ->get();
                $formattedDate = now()->toDateString();
                $formattedDate = now()->toDateString() . ' 00:00:00';
                $formattedDate1 = now()->toDateString() . ' 23:59:00';



                $ticket = DB::table('ticket_code')
                ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                ->join('tirage_record', 'tirage_record.id', '=', 'ticket_vendu.tirage_record_id')
                ->join('users', 'ticket_code.user_id', '=', 'users.id')
                ->whereBetween('ticket_code.created_at', [$formattedDate, $formattedDate1])
                ->where('ticket_code.compagnie_id', Session('loginId'))
                ->where(function ($query) {
                    $query->where('ticket_vendu.is_delete', 1)
                          ->orWhere('ticket_vendu.is_cancel', 1);
                })
                ->select(
                    'ticket_vendu.*',
                    'ticket_code.code as ticket_id',
                    'ticket_code.created_at as date',
                    'tirage_record.name as tirage',
                    'ticket_vendu.amount as montant',
                    'ticket_vendu.winning as gain',
                    'ticket_vendu.delete_at as date_delete',

                    'users.bank_name as bank'
                )
                ->orderByDesc('ticket_vendu.id')
                ->simplePaginate(50);

                return view('lister-ticket-delete', ['ticket' => $ticket, 'vendeur' => $user, 'tirage' => $tirage]);
            }
        } else {
            return view('login');
        }
    }
    public function show_boule(Request $request)
    {
        if (Session('loginId')) {
            $boule = DB::table('ticket_vendu')->where([
              
                ['id', '=', $request->input('id')],


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
    public function destroy(Request $request)
    {
        if (Session('loginId')) {
            $ticket_id = $request->input('id');
    
            // Optimized query to find the ticket and update it
            $updatedRows = TicketVendu::where([
                ['ticket_code.compagnie_id', '=', Session('loginId')],
                ['ticket_vendu.is_delete', '=', 0],
                ['ticket_vendu.is_cancel', '=', 0],
                ['ticket_vendu.id', '=', $ticket_id]
            ])
            ->join('ticket_code', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
            ->update([
                'ticket_vendu.is_delete' => 1,
                'ticket_vendu.delete_at' => now()
            ]);
    
            if ($updatedRows) {
                notify()->success('Fich la siprime avek sikse');
                return back();
            } else {
                notify()->error('Ere pase fich pa siprime');
                return back();
            }
        } else {
            return view('login');
        }

    }
}
