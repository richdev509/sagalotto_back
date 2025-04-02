<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\tirage_record;
use App\Models\branch;
use Illuminate\Contracts\Session\Session;
use Carbon\Carbon;

class rapportController extends Controller
{
    public function create_rapport(Request $request)
    {
        if (Session('loginId')) {
            if (!empty($request->input('date_debut') && !empty($request->input('date_fin')))) {
                if ($request->input('branch') == 'Tout') {
                    if ($request->input('bank') == 'Tout' && $request->input('tirage') == 'Tout') {

                        // First get the ticket codes in batches
                        $ticketCodes = DB::table('ticket_code')
                            ->where('compagnie_id', Session('loginId'))
                            ->whereDate('created_at', '>=', $request->input('date_debut'))
                            ->whereDate('created_at', '<=', $request->input('date_fin'))
                            ->orderBy('id') // Must specify orderBy for chunk()
                            ->select('code');

                        // Initialize result variables
                        $totals = [
                            'vente' => 0,
                            'perte' => 0,
                            'commission' => 0,
                            'ticket_win' => 0,
                            'ticket_lose' => 0,
                            'ticket_paye' => 0
                        ];

                        // Process in chunks of 1000 records at a time
                        $ticketCodes->chunk(50000, function ($codes) use (&$totals) {
                            $result = DB::table('ticket_vendu')
                                ->whereIn('ticket_code_id', $codes->pluck('code'))
                                ->where('is_cancel', 0)
                                ->where('is_delete', 0)
                                ->where('pending', 0)
                                ->select(
                                    DB::raw('COALESCE(SUM(amount), 0) as total_vente'),
                                    DB::raw('COALESCE(SUM(winning), 0) as total_perte'),
                                    DB::raw('COALESCE(SUM(commission), 0) as total_commission'),
                                    DB::raw('COUNT(CASE WHEN is_win = 1 THEN 1 END) as total_ticket_win'),
                                    DB::raw('COUNT(CASE WHEN is_win = 0 THEN 1 END) as total_ticket_lose'),
                                    DB::raw('COUNT(CASE WHEN is_payed = 1 THEN 1 END) as total_ticket_paye')
                                )
                                ->first();

                            // Accumulate the results
                            $totals['vente'] += $result->total_vente;
                            $totals['perte'] += $result->total_perte;
                            $totals['commission'] += $result->total_commission;
                            $totals['ticket_win'] += $result->total_ticket_win;
                            $totals['ticket_lose'] += $result->total_ticket_lose;
                            $totals['ticket_paye'] += $result->total_ticket_paye;
                        });

                        // Get additional data for the view
                        $user = User::where('compagnie_id', Session('loginId'))
                            ->select('id', 'name', 'bank_name')
                            ->get();

                        $tirage = tirage_record::where('compagnie_id', Session('loginId'))
                            ->select('id', 'name')
                            ->get();

                        $branch = branch::where('compagnie_id', Session('loginId'))
                            ->get();

                        return view('rapport', [
                            'vente' => $totals['vente'],
                            'perte' => $totals['perte'],
                            'ticket_win' => $totals['ticket_win'],
                            'ticket_lose' => $totals['ticket_lose'],
                            'ticket_paid' => $totals['ticket_paye'],
                            'date_debut' => $request->input('date_debut'),
                            'date_fin' => $request->input('date_fin'),
                            'bank' => 'Tout',
                            'tirage_' => 'Tout',
                            'is_calculated' => 1,
                            'vendeur' => $user,
                            'tirage' => $tirage,
                            'commission' => $totals['commission'],
                            'branch' => $branch,
                            'branch_' => 'Tout'
                        ]);
                    } elseif ($request->input('bank') == 'Tout' && $request->input('tirage') != 'Tout') {
                        $result = DB::table('ticket_code')
                            ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                            ->where([
                                ['ticket_code.compagnie_id', '=', Session('loginId')],
                                ['ticket_vendu.tirage_record_id', '=', $request->input('tirage')],
                                ['ticket_vendu.is_cancel', '=', 0],
                                ['ticket_vendu.is_delete', '=', 0],
                                ['ticket_vendu.pending', '=', 0],

                            ])
                            ->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                            ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                            ->select(
                                DB::raw('SUM(ticket_vendu.amount) as total_vente'),
                                DB::raw('SUM(ticket_vendu.winning) as total_perte'),
                                DB::raw('SUM(ticket_vendu.commission) as total_commission'),
                                DB::raw('COUNT(CASE WHEN ticket_vendu.is_win = 1 THEN 1 END) as total_ticket_win'),
                                DB::raw('COUNT(CASE WHEN ticket_vendu.is_win = 0 THEN 1 END) as total_ticket_lose'),
                                DB::raw('COUNT(CASE WHEN ticket_vendu.is_payed = 1 THEN 1 END) as total_ticket_paye')
                            )
                            ->first();

                        $vente = $result->total_vente;
                        $perte = $result->total_perte;
                        $commission = $result->total_commission;
                        $ticket_win = $result->total_ticket_win;
                        $ticket_lose = $result->total_ticket_lose;
                        $ticket_peye = $result->total_ticket_paye;


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
                        $branch = branch::where('compagnie_id', '=', Session('loginId'))->get();
                        return view('rapport', ['vente' => $vente, 'perte' => $perte, 'ticket_win' => $ticket_win, 'ticket_lose' => $ticket_lose, 'ticket_paid' => $ticket_peye, 'date_debut' => $request->input('date_debut'), 'date_fin' => $request->input('date_fin'), 'bank' => 'Tout', 'tirage_' => $name_tirage->name, 'is_calculated' => 1, 'vendeur' => $user, 'tirage' => $tirage, 'commission' => $commission, 'branch' => $branch, 'branch_' => 'Tout']);
                    } elseif ($request->input('bank') != 'Tout' && $request->input('tirage') == 'Tout') {

                        $ticketCodes = DB::table('ticket_code')
                            ->where([
                                ['compagnie_id', '=', Session('loginId')],
                                ['user_id', '=', $request->input('bank')]
                            ])
                            ->whereDate('created_at', '>=', $request->input('date_debut'))
                            ->whereDate('created_at', '<=', $request->input('date_fin'))
                            ->pluck('code');

                        // Then, use the list of ticket codes to query ticket_vendu
                        $result = DB::table('ticket_vendu')
                            ->whereIn('ticket_code_id', $ticketCodes)
                            ->where([
                                ['is_cancel', '=', 0],
                                ['is_delete', '=', 0],
                                ['pending', '=', 0]
                            ])
                            ->select(
                                DB::raw('SUM(amount) as total_vente'),
                                DB::raw('SUM(winning) as total_perte'),
                                DB::raw('SUM(commission) as total_commission'),
                                DB::raw('COUNT(CASE WHEN is_win = 1 THEN 1 END) as total_ticket_win'),
                                DB::raw('COUNT(CASE WHEN is_win = 0 THEN 1 END) as total_ticket_lose'),
                                DB::raw('COUNT(CASE WHEN is_payed = 1 THEN 1 END) as total_ticket_paye')
                            )
                            ->first();

                        $vente = $result->total_vente;
                        $perte = $result->total_perte;
                        $commission = $result->total_commission;
                        $ticket_win = $result->total_ticket_win;
                        $ticket_lose = $result->total_ticket_lose;
                        $ticket_peye = $result->total_ticket_paye;


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
                        $branch = branch::where('compagnie_id', '=', Session('loginId'))->get();
                        return view('rapport', ['vente' => $vente, 'perte' => $perte, 'ticket_win' => $ticket_win, 'ticket_lose' => $ticket_lose, 'ticket_paid' => $ticket_peye, 'date_debut' => $request->input('date_debut'), 'date_fin' => $request->input('date_fin'), 'bank' => $name_bank->bank_name, 'tirage_' => 'Tout', 'is_calculated' => 1, 'vendeur' => $user, 'tirage' => $tirage, 'commission' => $commission, 'branch' => $branch, 'branch_' => 'Tout']);
                    } elseif ($request->input('bank') != 'Tout' && $request->input('tirage') != 'Tout') {


                        $ticketCodes = DB::table('ticket_code')
                            ->where([
                                ['compagnie_id', '=', Session('loginId')],
                                ['user_id', '=', $request->input('bank')]


                            ])
                            ->whereDate('created_at', '>=', $request->input('date_debut'))
                            ->whereDate('created_at', '<=', $request->input('date_fin'))
                            ->pluck('code');

                        // Then, use the list of ticket codes to query ticket_vendu
                        $result = DB::table('ticket_vendu')
                            ->whereIn('ticket_code_id', $ticketCodes)
                            ->where([
                                ['tirage_record_id', '=', $request->input('tirage')],
                                ['is_cancel', '=', 0],
                                ['is_delete', '=', 0],
                                ['pending', '=', 0]
                            ])
                            ->select(
                                DB::raw('SUM(amount) as total_vente'),
                                DB::raw('SUM(winning) as total_perte'),
                                DB::raw('SUM(commission) as total_commission'),
                                DB::raw('COUNT(CASE WHEN is_win = 1 THEN 1 END) as total_ticket_win'),
                                DB::raw('COUNT(CASE WHEN is_win = 0 THEN 1 END) as total_ticket_lose'),
                                DB::raw('COUNT(CASE WHEN is_payed = 1 THEN 1 END) as total_ticket_paye')
                            )
                            ->first();

                        $vente = $result->total_vente;
                        $perte = $result->total_perte;
                        $commission = $result->total_commission;
                        $ticket_win = $result->total_ticket_win;
                        $ticket_lose = $result->total_ticket_lose;
                        $ticket_peye = $result->total_ticket_paye;


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
                        $branch = branch::where('compagnie_id', '=', Session('loginId'))->get();
                        return view('rapport', ['vente' => $vente, 'perte' => $perte, 'ticket_win' => $ticket_win, 'ticket_lose' => $ticket_lose, 'ticket_paid' => $ticket_peye, 'date_debut' => $request->input('date_debut'), 'date_fin' => $request->input('date_fin'), 'bank' => $name_bank->bank_name, 'tirage_' => $name_tirage->name, 'is_calculated' => 1, 'vendeur' => $user, 'tirage' => $tirage, 'commission' => $commission, 'branch' => $branch, 'branch_' => 'Tout']);
                    }
                } else {
                    if ($request->input('bank') == 'Tout' && $request->input('tirage') == 'Tout') {

                        $ticketCodes = DB::table('ticket_code')
                            ->where('compagnie_id', '=', Session('loginId'))
                            ->where('branch_id', '=', $request->input('branch'))
                            ->whereDate('created_at', '>=', $request->input('date_debut'))
                            ->whereDate('created_at', '<=', $request->input('date_fin'))
                            ->pluck('code');

                        // Then, use the list of ticket codes to query ticket_vendu
                        $result = DB::table('ticket_vendu')
                            ->whereIn('ticket_code_id', $ticketCodes)
                            ->where([
                                ['is_cancel', '=', 0],
                                ['is_delete', '=', 0],
                                ['pending', '=', 0]
                            ])
                            ->select(
                                DB::raw('SUM(amount) as total_vente'),
                                DB::raw('SUM(winning) as total_perte'),
                                DB::raw('SUM(commission) as total_commission'),
                                DB::raw('COUNT(CASE WHEN is_win = 1 THEN 1 END) as total_ticket_win'),
                                DB::raw('COUNT(CASE WHEN is_win = 0 THEN 1 END) as total_ticket_lose'),
                                DB::raw('COUNT(CASE WHEN is_payed = 1 THEN 1 END) as total_ticket_paye')
                            )->first();

                        $vente = $result->total_vente;
                        $perte = $result->total_perte;
                        $commission = $result->total_commission;
                        $ticket_win = $result->total_ticket_win;
                        $ticket_lose = $result->total_ticket_lose;
                        $ticket_peye = $result->total_ticket_paye;


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
                        //get branch
                        $branch = branch::where([
                            ['compagnie_id', '=', Session('loginId')]
                        ])->get();
                        $name_branch = branch::where([
                            ['id', '=', $request->input('branch')]
                        ])->select('name')->first();
                        return view('rapport', ['vente' => $vente, 'perte' => $perte, 'ticket_win' => $ticket_win, 'ticket_lose' => $ticket_lose, 'ticket_paid' => $ticket_peye, 'date_debut' => $request->input('date_debut'), 'date_fin' => $request->input('date_fin'), 'bank' => 'Tout', 'tirage_' => 'Tout', 'is_calculated' => 1, 'vendeur' => $user, 'tirage' => $tirage, 'commission' => $commission, 'branch' => $branch, 'branch_' => $name_branch->name]);
                    } elseif ($request->input('bank') == 'Tout' && $request->input('tirage') != 'Tout') {
                        $result = DB::table('ticket_code')
                            ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                            ->where([
                                ['ticket_code.compagnie_id', '=', Session('loginId')],
                                ['ticket_code.branch_id', '=', $request->input('branch')],
                                ['ticket_vendu.tirage_record_id', '=', $request->input('tirage')],
                                ['ticket_vendu.is_cancel', '=', 0],
                                ['ticket_vendu.is_delete', '=', 0],
                                ['ticket_vendu.pending', '=', 0],

                            ])
                            ->whereDate('ticket_code.created_at', '>=', $request->input('date_debut'))
                            ->whereDate('ticket_code.created_at', '<=', $request->input('date_fin'))
                            ->select(
                                DB::raw('SUM(ticket_vendu.amount) as total_vente'),
                                DB::raw('SUM(ticket_vendu.winning) as total_perte'),
                                DB::raw('SUM(ticket_vendu.commission) as total_commission'),
                                DB::raw('COUNT(CASE WHEN ticket_vendu.is_win = 1 THEN 1 END) as total_ticket_win'),
                                DB::raw('COUNT(CASE WHEN ticket_vendu.is_win = 0 THEN 1 END) as total_ticket_lose'),
                                DB::raw('COUNT(CASE WHEN ticket_vendu.is_payed = 1 THEN 1 END) as total_ticket_paye')
                            )
                            ->first();

                        $vente = $result->total_vente;
                        $perte = $result->total_perte;
                        $commission = $result->total_commission;
                        $ticket_win = $result->total_ticket_win;
                        $ticket_lose = $result->total_ticket_lose;
                        $ticket_peye = $result->total_ticket_paye;


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
                        $branch = branch::where([
                            ['compagnie_id', '=', Session('loginId')]
                        ])->get();
                        $name_branch = branch::where([
                            ['id', '=', $request->input('branch')]
                        ])->select('name')->first();
                        return view('rapport', ['vente' => $vente, 'perte' => $perte, 'ticket_win' => $ticket_win, 'ticket_lose' => $ticket_lose, 'ticket_paid' => $ticket_peye, 'date_debut' => $request->input('date_debut'), 'date_fin' => $request->input('date_fin'), 'bank' => 'Tout', 'tirage_' => $name_tirage->name, 'is_calculated' => 1, 'vendeur' => $user, 'tirage' => $tirage, 'commission' => $commission, 'branch' => $branch, 'branch_' => $name_branch->name]);
                    } elseif ($request->input('bank') != 'Tout' && $request->input('tirage') == 'Tout') {

                        $ticketCodes = DB::table('ticket_code')
                            ->where([
                                ['compagnie_id', '=', Session('loginId')],
                                ['user_id', '=', $request->input('bank')],
                                ['branch_id', '=', $request->input('branch')]
                            ])
                            ->whereDate('created_at', '>=', $request->input('date_debut'))
                            ->whereDate('created_at', '<=', $request->input('date_fin'))
                            ->pluck('code');

                        // Then, use the list of ticket codes to query ticket_vendu
                        $result = DB::table('ticket_vendu')
                            ->whereIn('ticket_code_id', $ticketCodes)
                            ->where([
                                ['is_cancel', '=', 0],
                                ['is_delete', '=', 0],
                                ['pending', '=', 0]
                            ])
                            ->select(
                                DB::raw('SUM(amount) as total_vente'),
                                DB::raw('SUM(winning) as total_perte'),
                                DB::raw('SUM(commission) as total_commission'),
                                DB::raw('COUNT(CASE WHEN is_win = 1 THEN 1 END) as total_ticket_win'),
                                DB::raw('COUNT(CASE WHEN is_win = 0 THEN 1 END) as total_ticket_lose'),
                                DB::raw('COUNT(CASE WHEN is_payed = 1 THEN 1 END) as total_ticket_paye')
                            )
                            ->first();

                        $vente = $result->total_vente;
                        $perte = $result->total_perte;
                        $commission = $result->total_commission;
                        $ticket_win = $result->total_ticket_win;
                        $ticket_lose = $result->total_ticket_lose;
                        $ticket_peye = $result->total_ticket_paye;


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
                        $branch = branch::where([
                            ['compagnie_id', '=', Session('loginId')]
                        ])->get();
                        $name_branch = branch::where([
                            ['id', '=', $request->input('branch')]
                        ])->select('name')->fist();
                        return view('rapport', ['vente' => $vente, 'perte' => $perte, 'ticket_win' => $ticket_win, 'ticket_lose' => $ticket_lose, 'ticket_paid' => $ticket_peye, 'date_debut' => $request->input('date_debut'), 'date_fin' => $request->input('date_fin'), 'bank' => $name_bank->bank_name, 'tirage_' => 'Tout', 'is_calculated' => 1, 'vendeur' => $user, 'tirage' => $tirage, 'commission' => $commission, 'branch' => $branch, 'branch_' => $name_branch->name]);
                    } elseif ($request->input('bank') != 'Tout' && $request->input('tirage') != 'Tout') {

                        $ticketCodes = DB::table('ticket_code')
                            ->where([
                                ['compagnie_id', '=', Session('loginId')],
                                ['user_id', '=', $request->input('bank')],
                                ['branch_id', '=', $request->input('branch')]


                            ])
                            ->whereDate('created_at', '>=', $request->input('date_debut'))
                            ->whereDate('created_at', '<=', $request->input('date_fin'))
                            ->pluck('code');

                        // Then, use the list of ticket codes to query ticket_vendu
                        $result = DB::table('ticket_vendu')
                            ->whereIn('ticket_code_id', $ticketCodes)
                            ->where([
                                ['tirage_record_id', '=', $request->input('tirage')],
                                ['is_cancel', '=', 0],
                                ['is_delete', '=', 0],
                                ['pending', '=', 0]
                            ])
                            ->select(
                                DB::raw('SUM(amount) as total_vente'),
                                DB::raw('SUM(winning) as total_perte'),
                                DB::raw('SUM(commission) as total_commission'),
                                DB::raw('COUNT(CASE WHEN is_win = 1 THEN 1 END) as total_ticket_win'),
                                DB::raw('COUNT(CASE WHEN is_win = 0 THEN 1 END) as total_ticket_lose'),
                                DB::raw('COUNT(CASE WHEN is_payed = 1 THEN 1 END) as total_ticket_paye')
                            )
                            ->first();

                        $vente = $result->total_vente;
                        $perte = $result->total_perte;
                        $commission = $result->total_commission;
                        $ticket_win = $result->total_ticket_win;
                        $ticket_lose = $result->total_ticket_lose;
                        $ticket_peye = $result->total_ticket_paye;


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

                        $branch = branch::where([
                            ['compagnie_id', '=', Session('loginId')],
                            ['is_delete', '=', 0],
                        ])->get();
                        $name_branch = branch::where([
                            ['id', '=', $request->input('branch')]
                        ])->select('name')->first();
                        return view('rapport', ['vente' => $vente, 'perte' => $perte, 'ticket_win' => $ticket_win, 'ticket_lose' => $ticket_lose, 'ticket_paid' => $ticket_peye, 'date_debut' => $request->input('date_debut'), 'date_fin' => $request->input('date_fin'), 'bank' => $name_bank->bank_name, 'tirage_' => $name_tirage->name, 'is_calculated' => 1, 'vendeur' => $user, 'tirage' => $tirage, 'commission' => $commission, 'branch' => $branch, 'branch_' => $name_branch->name]);
                    }
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
                $branch = Branch::where([
                    ['compagnie_id', '=', Session('loginId')],
                    ['is_delete', '=', 0],
                ])->get();
                return view('rapport', ['vendeur' => $user, 'tirage' => $tirage, 'is_calculated' => 0, 'branch' => $branch]);
            }
        } else {
            return view('login');
        }
    }
    public function create_rapport2(Request $request)
    {

        if (Session('loginId')) {
            $loginId = Session('loginId');
            $dateDebut = $request->input('date_debut');
            $dateFin = $request->input('date_fin');
            $branch = $request->input('branch');

            if (!empty($dateDebut) && !empty($dateFin)) {

                if ($request->input('branch') != 'tout') {
                    $userIds = DB::table('ticket_code')
                        ->where('compagnie_id', '=', $loginId)
                        ->whereDate('ticket_code.created_at', '>=', $dateDebut)
                        ->whereDate('ticket_code.created_at', '<=', $dateFin)
                        ->where('ticket_code.branch_id', '=', $branch)
                        ->distinct()
                        ->orderBy('user_id', 'asc')

                        ->pluck('user_id');
                    $data = collect();
                    foreach ($userIds as $user) {
                        $fichcode = DB::table('ticket_code')
                            ->where('compagnie_id', '=', $loginId)
                            ->where('user_id', '=', $user)
                            ->whereDate('ticket_code.created_at', '>=', $dateDebut)
                            ->whereDate('ticket_code.created_at', '<=', $dateFin)
                            ->where('ticket_code.branch_id', '=', $branch)
                            ->pluck('code');

                        $result = DB::table('ticket_vendu')
                            ->whereIn('ticket_code_id', $fichcode)
                            ->where([
                                ['is_cancel', '=', 0],
                                ['is_delete', '=', 0],
                                ['pending', '=', 0]
                            ])
                            ->select(
                                DB::raw('SUM(ticket_vendu.amount) as vente'),
                                DB::raw('SUM(ticket_vendu.winning) as perte'),
                                DB::raw('SUM(ticket_vendu.commission) as commission'),
                            )
                            ->first();
                        $data->push(['bank_name' => $user, 'vente' => $result->vente, 'perte' => $result->perte, 'commission' => $result->commission]);
                    }
                    // dd($vendeur);
                    $bank = User::where([
                        ['compagnie_id', '=', Session('loginId')],
                        ['is_delete', '=', 0],
                    ])->get();

                    $branch = Branch::where([
                        ['compagnie_id', '=', Session('loginId')],
                        ['is_delete', '=', 0],
                    ])->get();

                    //control historique
                    $control = DB::table('tbl_control')->where([
                        ['tbl_control.compagnie_id', '=', Session('loginId')],
                    ])->join('users', 'users.code', '=', 'tbl_control.id_user')
                        ->select('tbl_control.*', 'users.bank_name')
                        ->orderByDesc('date_rapport')
                        ->limit('50')
                        ->get();
                    return view('raportsecond', ['bank' => $bank, 'control' => $control, 'vendeur' => $data, 'date_debut' => $dateDebut, 'date_fin' => $dateFin, 'branch' => $branch]);
                } else {

                    $userIds = DB::table('ticket_code')
                        ->where('compagnie_id', '=', $loginId)
                        ->whereDate('ticket_code.created_at', '>=', $dateDebut)
                        ->whereDate('ticket_code.created_at', '<=', $dateFin)
                        ->distinct()
                        ->orderBy('user_id', 'asc')
                        ->pluck('user_id');
                    $data = collect();
                    foreach ($userIds as $user) {
                        $fichcode = DB::table('ticket_code')
                            ->where('compagnie_id', '=', $loginId)
                            ->where('user_id', '=', $user)
                            ->whereDate('ticket_code.created_at', '>=', $dateDebut)
                            ->whereDate('ticket_code.created_at', '<=', $dateFin)
                            ->pluck('code');

                        $result = DB::table('ticket_vendu')
                            ->whereIn('ticket_code_id', $fichcode)
                            ->where([
                                ['is_cancel', '=', 0],
                                ['is_delete', '=', 0],
                                ['pending', '=', 0]
                            ])
                            ->select(
                                DB::raw('SUM(ticket_vendu.amount) as vente'),
                                DB::raw('SUM(ticket_vendu.winning) as perte'),
                                DB::raw('SUM(ticket_vendu.commission) as commission'),
                            )
                            ->first();
                        $data->push(['bank_name' => $user, 'vente' => $result->vente, 'perte' => $result->perte, 'commission' => $result->commission]);
                    }

                    // dd($vendeur);
                    $bank = User::where([
                        ['compagnie_id', '=', Session('loginId')],
                        ['is_delete', '=', 0],
                    ])->get();
                    $branch = Branch::where([
                        ['compagnie_id', '=', Session('loginId')],
                        ['is_delete', '=', 0],
                    ])->get();
                    //control historique
                    $control = DB::table('tbl_control')->where([
                        ['tbl_control.compagnie_id', '=', Session('loginId')],
                    ])->join('users', 'users.code', '=', 'tbl_control.id_user')
                        ->select('tbl_control.*', 'users.bank_name')
                        ->orderByDesc('date_rapport')
                        ->limit('50')
                        ->get();
                    return view('raportsecond', ['bank' => $bank, 'control' => $control, 'vendeur' => $data, 'date_debut' => $dateDebut, 'date_fin' => $dateFin, 'branch' => $branch]);
                }
            } else {
                $date1 = Carbon::now()->format('Y-m-d') . ' 00:00:00';
                $date2 = Carbon::now()->format('Y-m-d') . ' 23:59:59';
                $userIds = DB::table('ticket_code')
                    ->where('compagnie_id', '=', $loginId)
                    ->whereBetween('ticket_code.created_at', [$date1, $date2])
                    ->distinct()
                    ->orderBy('user_id', 'asc')
                    ->pluck('user_id');
                $data = collect();
                foreach ($userIds as $user) {
                    $fichcode = DB::table('ticket_code')
                        ->where('compagnie_id', '=', $loginId)
                        ->where('user_id', '=', $user)
                        ->whereBetween('ticket_code.created_at', [$date1, $date2])
                        ->distinct()
                        ->pluck('code');

                    $result = DB::table('ticket_vendu')
                        ->whereIn('ticket_code_id', $fichcode)
                        ->where([
                            ['is_cancel', '=', 0],
                            ['is_delete', '=', 0],
                            ['pending', '=', 0]
                        ])
                        ->select(
                            DB::raw('SUM(ticket_vendu.amount) as vente'),
                            DB::raw('SUM(ticket_vendu.winning) as perte'),
                            DB::raw('SUM(ticket_vendu.commission) as commission'),
                        )
                        ->first();
                    $data->push(['bank_name' => $user, 'vente' => $result->vente, 'perte' => $result->perte, 'commission' => $result->commission]);
                }



                // dd($vendeur);
                $bank = User::where([
                    ['compagnie_id', '=', Session('loginId')],
                    ['is_delete', '=', 0],
                ])->get();
                $branch = Branch::where([
                    ['compagnie_id', '=', Session('loginId')],
                    ['is_delete', '=', 0],
                ])->get();

                //control historique
                $control = DB::table('tbl_control')->where([
                    ['tbl_control.compagnie_id', '=', Session('loginId')],
                ])->join('users', 'users.code', '=', 'tbl_control.id_user')
                    ->select('tbl_control.*', 'users.bank_name')
                    ->orderByDesc('date_rapport')
                    ->limit('50')
                    ->get();
                return view('raportsecond', ['bank' => $bank, 'control' => $control, 'vendeur' => $data, 'date_debut' => Carbon::now()->format('Y-m-d'), 'date_fin' => Carbon::now()->format('Y-m-d'), 'period' => 'Tout', 'branch' => $branch]);
            }
        } else {
            return view('login');
        }
    }
    public function get_control(Request $request)
    {
        $user_id = $request->input('user');
        $date = $request->input('date');

        $user = User::where([
            ['id', '=', $user_id]
        ])->first();
        $control = DB::table('tbl_control')->where([
            ['id_user', '=', $user_id],
            ['compagnie_id', '=', Session('loginId')]
        ])->whereDate('date_rapport', '=', $date)->first();
        //get the control if it exist
        if ($control) {
            return response()->json([
                'control' => 1,
                'status' => 'true',
                'bank_code' => $user->code,
                'bank' => $user->bank_name,
                'montant' => $control->montant,
                'balance' => $control->balance,
                'date' => $date

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
        $montant = $vente - ($perte + $commission);

        return response()->json([
            'status' => 'true',
            'control' => 0,
            'bank_code' => $user->code,
            'bank' => $user->bank_name,
            'montant' => $montant,
            'date' => $date

        ]);
    }
    public function save_control(Request $request)
    {
        $validator = $request->validate([
            "vendeur" => "required",
            'ddate' => 'required',
            'amount' => 'required',
            'amount_' => 'required',
        ]);
        //get user id
        if ($request->input('amount_') > $request->input('amount')) {
            return response()->json([
                'save' => 0,
                'status' => 'false',
                'message' => 'montan pa dwe depase montan rapo a'
            ]);
        }
        $control = DB::table('tbl_control')->where([
            ['compagnie_id', '=', Session('loginId')],
            ['date_rapport', '=', $request->input('ddate')],
            ['id_user', '=', $request->input('vendeur')]
        ])->first();
        if ($control) {
            return response()->json([
                'save' => 0,
                'status' => 'false',
                'message' => 'rapo sa anregistre deja'
            ]);
        } else {
            $query = DB::table('tbl_control')->insertGetId([
                'compagnie_id' => Session('loginId'),
                'date_rapport' => $request->input('ddate'),
                'id_user' => $request->input('vendeur'),
                'montant' => $request->input('amount_'),
                'balance' => $request->input('amount') - $request->input('amount_'),
                'created_at' => Carbon::now()
            ]);
            return response()->json([
                'save' => 1,
                'status' => 'true',
                'message' => 'anregistrement fet ak sikse'
            ]);
        }
    }
}
