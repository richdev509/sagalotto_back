<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\tirage_record;
use Exception;
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
                        $ticketCodes->chunk(25000, function ($codes) use (&$totals) {
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
                    // Pluck-based per-user totals (same pattern as create_rapport)
                    $userIds = DB::table('ticket_code')
                        ->where('compagnie_id', $loginId)
                        ->where('branch_id', $branch)
                        ->whereDate('created_at', '>=', $dateDebut)
                        ->whereDate('created_at', '<=', $dateFin)
                        ->distinct()
                        ->orderBy('user_id', 'asc')
                        ->pluck('user_id');

                    $data = collect();
                    foreach ($userIds as $user) {
                        $codes = DB::table('ticket_code')
                            ->where('compagnie_id', $loginId)
                            ->where('branch_id', $branch)
                            ->where('user_id', $user)
                            ->whereDate('created_at', '>=', $dateDebut)
                            ->whereDate('created_at', '<=', $dateFin)
                            ->pluck('code');

                        $result = DB::table('ticket_vendu')
                            ->whereIn('ticket_code_id', $codes)
                            ->where([
                                ['is_cancel', '=', 0],
                                ['is_delete', '=', 0],
                                ['pending', '=', 0]
                            ])
                            ->select(
                                DB::raw('COALESCE(SUM(amount), 0) as vente'),
                                DB::raw('COALESCE(SUM(winning), 0) as perte'),
                                DB::raw('COALESCE(SUM(commission), 0) as commission')
                            )
                            ->first();

                        $data->push([
                            'bank_name' => $user,
                            'vente' => $result->vente ?? 0,
                            'perte' => $result->perte ?? 0,
                            'commission' => $result->commission ?? 0
                        ]);
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

                    // Pluck-based per-user totals for all branches
                    $userIds = DB::table('ticket_code')
                        ->where('compagnie_id', $loginId)
                        ->whereDate('created_at', '>=', $dateDebut)
                        ->whereDate('created_at', '<=', $dateFin)
                        ->distinct()
                        ->orderBy('user_id', 'asc')
                        ->pluck('user_id');

                    $data = collect();
                    foreach ($userIds as $user) {
                        $codes = DB::table('ticket_code')
                            ->where('compagnie_id', $loginId)
                            ->where('user_id', $user)
                            ->whereDate('created_at', '>=', $dateDebut)
                            ->whereDate('created_at', '<=', $dateFin)
                            ->pluck('code');

                        $result = DB::table('ticket_vendu')
                            ->whereIn('ticket_code_id', $codes)
                            ->where([
                                ['is_cancel', '=', 0],
                                ['is_delete', '=', 0],
                                ['pending', '=', 0]
                            ])
                            ->select(
                                DB::raw('COALESCE(SUM(amount), 0) as vente'),
                                DB::raw('COALESCE(SUM(winning), 0) as perte'),
                                DB::raw('COALESCE(SUM(commission), 0) as commission')
                            )
                            ->first();

                        $data->push([
                            'bank_name' => $user,
                            'vente' => $result->vente ?? 0,
                            'perte' => $result->perte ?? 0,
                            'commission' => $result->commission ?? 0
                        ]);
                    }
                    //dd($data);
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
    public function create_control(Request $request)
    {
        if (Session('loginId')) {
            $loginId = Session('loginId');
            $dateDebut = $request->input('date_debut');
            $dateFin = $request->input('date_fin');
            $branch = $request->input('branch');

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
            ])->join('users', 'users.id', '=', 'tbl_control.id_user')
                ->select('tbl_control.*', 'users.bank_name')
                ->orderByDesc('date_rapport')
                ->limit('100')
                ->get();
            return view('control', ['bank' => $bank, 'control' => $control, 'date_debut' => Carbon::now()->format('Y-m-d'), 'date_fin' => Carbon::now()->format('Y-m-d'), 'period' => 'Tout', 'branch' => $branch]);
        } else {
            return view('login');
        }
    }
    public function get_control(Request $request)
    {
        $user_id = $request->input('user');
        $date_debut = $request->input('date1');
        $date_fin = $request->input('date2');
        try {
            $user = User::where([
                ['id', '=', $user_id]
            ])->first();
            // $control = DB::table('tbl_control')->where([
            //     ['id_user', '=', $user_id],
            //     ['compagnie_id', '=', Session('loginId')]
            // ])->whereDate('date_rapport', '=', $date)->first();
            // //get the control if it exist
            // if ($control) {
            //     return response()->json([
            //         'control' => 1,
            //         'status' => 'true',
            //         'bank_code' => $user->code,
            //         'bank' => $user->bank_name,
            //         'montant' => $control->montant,
            //         'balance' => $control->balance,
            //         'date' => $date

            //     ]);
            // }
            $result = DB::table('ticket_code')
                ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                ->where('ticket_code.compagnie_id', Session('loginId'))
                ->where('ticket_code.user_id', $user_id)
                ->where('ticket_vendu.is_cancel', 0)
                ->where('ticket_vendu.is_delete', 0)
                ->where('ticket_vendu.pending', 0)
                ->whereBetween('ticket_code.created_at', [$date_debut.' 00:00:00', $date_fin.' 23:59:59'])
                ->selectRaw('SUM(ticket_vendu.commission) as commission, SUM(ticket_vendu.amount) as amount , SUM(ticket_vendu.winning) as perte')
                ->groupBy('ticket_code.user_id')
                ->first();
            if ($result) {
                $montant = $result->amount - ($result->perte + $result->commission);
            } else {
                $montant = 0;
                $result = (object) [
                    'amount' => 0,
                    'perte' => 0,
                    'commission' => 0
                ];
            }

            $montant = $result->amount - ($result->perte + $result->commission);

            return response()->json([
                'status' => 'true',
                'control' => 0,
                'bank_code' => $user->id,
                'bank' => $user->bank_name,
                'montant' => round($montant, 0),
                'devise' => $user->devise ?? 'HTG',
                'date' => $date_debut,
                'date1' => $date_fin


            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'false',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function get_control_date(Request $request)
    {
        $user_id = $request->input('user');
        $control_date = DB::table('tbl_control')->where([
            ['id_user', '=', $user_id],
            ['compagnie_id', '=', Session('loginId')]
        ])->orderByDesc('id')
            ->first();
        //get the control if it exist
        if ($control_date) {
            return response()->json([
                'status' => 'true',
                'date' => \Carbon\Carbon::parse($control_date->date_fin)->addDays(1)->format('Y-m-d')

            ]);
        }
        $control_date = DB::table('ticket_code')->where([
            ['ticket_code.compagnie_id', '=', Session('loginId')],
            ['ticket_code.user_id', '=', $user_id],
            ['ticket_vendu.is_cancel', '=', 0],
            ['ticket_vendu.is_delete', '=', 0],

        ])->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
            ->orderBy('ticket_vendu.id')
            ->first();
        if ($control_date) {
            return response()->json([
                'status' => 'true',
                'date' =>  \Carbon\Carbon::parse($control_date->created_at)->format('Y-m-d')

            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'date' => null

            ]);
        }
    }
    public function save_control(Request $request)
    {
        $validator = $request->validate([
            "vendeur" => "required",
            'ddate' => 'required',
            'edate' => 'required',
            'amount' => 'required',
            'amount_' => 'required',
        ]);
        try {
            if ($request->input('amount') > 0) {

                if ($request->input('amount_') > $request->input('amount')) {
                    return response()->json([
                        'save' => 0,
                        'status' => 'false',
                        'message' => 'montan pa dwe depase montan rapo a'
                    ]);
                }
            }
            if ($request->input('amount') < 0) {
                $am = $request->input('amount') * -1;

                if ($request->input('amount_') > $am) {
                    return response()->json([
                        'save' => 0,
                        'status' => 'false',
                        'message' => 'montan pa dwe depase montan rapo a'
                    ]);
                }
            }


            //get user id

            $control = DB::table('tbl_control')->where([
                ['compagnie_id', '=', Session('loginId')],
                ['date_rapport', '=', $request->input('ddate')],
                ['date_fin', '=', $request->input('edate')],
                ['id_user', '=', $request->input('vendeur')]
            ])->first();

            if ($control) {
                $requestedAmount = $request->input('amount_');

                // Validate the requested amount
                if ($requestedAmount <= 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Montan an dwe yon valè pozitif.'
                    ], 422); // 422 Unprocessable Entity
                }

                // Check if balance is sufficient
                if ($control->balance < $requestedAmount) {
                    return response()->json([
                        'save' => 1,
                        'status' => 'false',
                        'message' => 'Montan an pa dwe depase balans lan.'
                    ], 400); // 400 Bad Request
                }

                // Update the balance
                $contr = DB::table('tbl_control')
                    ->where('id', $control->id)
                    ->update([
                        'balance' => $control->balance - $requestedAmount,
                        'updated_at' => Carbon::now()
                    ]);

                if ($contr) {
                    return response()->json([
                        'save' => 1,
                        'status' => 'true',
                        'message' => 'Balans lan modifye ak siksè',
                    ]);
                } else {
                    return response()->json([
                        'save' => 1,
                        'status' => 'false',
                        'message' => 'Echèk nan modifye balans lan'
                    ], 500); // 500 Internal Server Error
                }
            } else {
                if ($request->input('amount') > 0) {
                    $query = DB::table('tbl_control')->insertGetId([
                        'compagnie_id' => Session('loginId'),
                        'date_rapport' => $request->input('ddate'),
                        'date_fin' => $request->input('edate'),
                        'id_user' => $request->input('vendeur'),
                        'montant' => $request->input('amount'),
                        'balance' => $request->input('amount') - $request->input('amount_'),
                        'created_at' => Carbon::now()
                    ]);
                } else {
                    $query = DB::table('tbl_control')->insertGetId([
                        'compagnie_id' => Session('loginId'),
                        'date_rapport' => $request->input('ddate'),
                        'date_fin' => $request->input('edate'),
                        'id_user' => $request->input('vendeur'),
                        'montant' => $request->input('amount'),
                        'balance' => $request->input('amount') * -1 - ($request->input('amount_')),
                        'created_at' => Carbon::now()
                    ]);
                }


                return response()->json([
                    'save' => 1,
                    'status' => 'true',
                    'message' => 'anregistrement fet ak sikse'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'save' => 0,
                'status' => 'false',
                'message' => 'problem' . $e->getMessage()
            ]);
        }
    }
}
