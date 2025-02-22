<?php

namespace App\Http\Controllers\superviseur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\branch;
use App\Models\company;
use App\Models\BoulGagnant;
use App\Models\ticket_code;
use App\Models\User;
use App\Models\TicketVendu;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class adminController extends Controller
{
    public function admin()
    {
        if (Session('branchId')) {

            $date1 = Carbon::now()->format('Y-m-d').' 00:00:00';
            $date2 = Carbon::now()->format('Y-m-d').' 23:59:59';
            $ticketCodes = DB::table('ticket_code')
                ->where('branch_id', '=', Session('branchId'))
                ->whereBetween('created_at', [$date1, $date2])
                //->whereDate('created_at', '=', $today)
                ->pluck('code');

            $data = DB::table('ticket_vendu')
                ->whereIn('ticket_code_id', $ticketCodes)
                ->where([
                    ['is_delete', '=', 0],
                    ['is_cancel', '=', 0],
                    ['pending', '=', 0],

                ])
                ->selectRaw('SUM(ticket_vendu.amount) as total_amount, 
                             SUM(ticket_vendu.winning) as total_winning, 
                             SUM(ticket_vendu.commission) as total_commission')
                ->first();

            $vente = $data->total_amount;
            $perte = $data->total_winning;
            $commission = $data->total_commission;



            $lista = BoulGagnant::where('compagnie_id', session('compagnieId'))
                ->latest('created_at')
                ->take(3)
                ->get();

            $list = [];


            foreach ($lista as $boulGagnant) {
                $codes = ticket_code::where('branch_id', session('branchId'))
                    ->whereDate('created_at', $boulGagnant->created_)
                    ->pluck('code')
                    ->toArray();

                $vent = TicketVendu::whereIn('ticket_code_id', $codes)
                    ->where('tirage_record_id', $boulGagnant->tirage_id)->where('is_delete', 0)->where('is_cancel', 0)
                    ->sum('amount');
                /* $vente =TicketVendu::where('tirage_record_id',$boulGagnant->tirage_id)
                    ->whereDate('created_at', $boulGagnant->created_)
                    ->sum('amount');
                    dd($vente,);*/
                $pert = TicketVendu::whereIn('ticket_code_id', $codes)
                    ->where('tirage_record_id', $boulGagnant->tirage_id)->where('is_delete', 0)->where('is_cancel', 0)
                    ->sum('winning');

                $commissio = TicketVendu::whereIn('ticket_code_id', $codes)
                    ->where('tirage_record_id', $boulGagnant->tirage_id)->where('is_delete', 0)->where('is_cancel', 0)
                    ->sum('commission');
                $tirageName = $boulGagnant->tirage_record->name;
                $list[] = [
                    'boulGagnant' => $boulGagnant,
                    'vent' => $vent,
                    'pert' => $pert,
                    'commissio' => $commissio,
                    'name' => $tirageName


                ];
            }


            return view('superviseur.admin', ['vente' => $vente, 'perte' => $perte, 'list' => $list, 'commission' => $commission]);
        } else {
            return view('/superviseur/login');
        }
    }
    public function login(Request $request)
    {
        $username =  $request->input('username');
        $password =  $request->input('password');

        if (empty($username) || empty($password)) {
            notify()->error('ranpli tout chan yo');
            return back();
        } else {

            $user = branch::where([
                ['agent_username', '=', $username],
            ])->first();
            //try to know if user
            if ($user) {
                //User found let tcheck the password
                if (Hash::check($password, $user->agent_password)) {
                    //Password match let find if user not block
                    if ($user->is_block != '1') {
                        $request->session()->put('branchId', $user->id);
                        $request->session()->put('fullname', $user->agent_fullname);
                        $request->session()->put('role', 'superviseur');
                        $request->session()->put('percent', $user->percent);

                        $compagnie = company::where('id', $user->compagnie_id)->first();
                        $request->session()->put('logo', $compagnie->logo);
                        $request->session()->put('name', $compagnie->name);
                        $request->session()->put('compagnieId', $compagnie->id);
                        notify()->success('Bienvenue ' . $user->agent_fullname);
                        return redirect('/superviseur');
                    } else {
                        notify()->error('kont ou bloke kontakte sagacelotto');
                        return redirect('/superviseur/login');
                    }
                } else {
                    notify()->error('modepass la pa bon');

                    return redirect('/superviseur/login');
                }
            } else {
                notify()->error('non itilizate a inkorek');

                return redirect('/superviseur/login')->with('error', 'Utilisateur non trouve');
            }
        }
    }
    public function create_rapport(Request $request)
    {
        if (Session('loginId')) {
            if (!empty($request->input('date_debut') && !empty($request->input('date_fin')))) {
                if ($request->input('branch') == 'Tout') {
                    if ($request->input('bank') == 'Tout' && $request->input('tirage') == 'Tout') {

                        $ticketCodes = DB::table('ticket_code')
                            ->where('compagnie_id', '=', Session('loginId'))
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
                        $branch = branch::where('compagnie_id', '=', Session('loginId'))->get();
                        return view('rapport', ['vente' => $vente, 'perte' => $perte, 'ticket_win' => $ticket_win, 'ticket_lose' => $ticket_lose, 'ticket_paid' => $ticket_peye, 'date_debut' => $request->input('date_debut'), 'date_fin' => $request->input('date_fin'), 'bank' => 'Tout', 'tirage_' => 'Tout', 'is_calculated' => 1, 'vendeur' => $user, 'tirage' => $tirage, 'commission' => $commission, 'branch' => $branch,'branch_'=>'Tout']);
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
                        return view('rapport', ['vente' => $vente, 'perte' => $perte, 'ticket_win' => $ticket_win, 'ticket_lose' => $ticket_lose, 'ticket_paid' => $ticket_peye, 'date_debut' => $request->input('date_debut'), 'date_fin' => $request->input('date_fin'), 'bank' => 'Tout', 'tirage_' => $name_tirage->name, 'is_calculated' => 1, 'vendeur' => $user, 'tirage' => $tirage, 'commission' => $commission, 'branch' => $branch,'branch_'=>'Tout']);
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
                        return view('rapport', ['vente' => $vente, 'perte' => $perte, 'ticket_win' => $ticket_win, 'ticket_lose' => $ticket_lose, 'ticket_paid' => $ticket_peye, 'date_debut' => $request->input('date_debut'), 'date_fin' => $request->input('date_fin'), 'bank' => $name_bank->bank_name, 'tirage_' => 'Tout', 'is_calculated' => 1, 'vendeur' => $user, 'tirage' => $tirage, 'commission' => $commission, 'branch' => $branch,'branch_'=>'Tout']);
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
                        return view('rapport', ['vente' => $vente, 'perte' => $perte, 'ticket_win' => $ticket_win, 'ticket_lose' => $ticket_lose, 'ticket_paid' => $ticket_peye, 'date_debut' => $request->input('date_debut'), 'date_fin' => $request->input('date_fin'), 'bank' => $name_bank->bank_name, 'tirage_' => $name_tirage->name, 'is_calculated' => 1, 'vendeur' => $user, 'tirage' => $tirage, 'commission' => $commission, 'branch' => $branch,'branch_'=>'Tout']);
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

        if (Session('branchId')) {
            $loginId = Session('branchId');
            $dateDebut = $request->input('date_debut');
            $dateFin= $request->input('date_fin');
            $dateDebut1 = $request->input('date_debut').' 00:00:00';
            $dateFin1 = $request->input('date_fin').' 23:59:59';

            if (!empty($dateDebut) && !empty($dateFin)) {

                if ($request->input('period') == 'matin') {
                    $userIds = DB::table('ticket_code')
                        ->where('branch_id', '=', $loginId)
                        ->whereBetween('ticket_code.created_at', [$dateDebut1, $dateFin1])
                        ->whereTime('ticket_code.created_at', '<=', '14:30:00')
                        ->distinct()
                        ->pluck('user_id');
                    $data = collect();
                    foreach ($userIds as $user) {
                        $fichcode = DB::table('ticket_code')
                            ->where('branch_id', '=', $loginId)
                            ->where('user_id', '=', $user)
                            ->where('ticket_code.created_at', '>=', $dateDebut)
                            ->whereTime('ticket_code.created_at', '<=', '14:30:00')
                            ->pluck('code');

                        $result = DB::table('ticket_vendu')
                            ->whereIn('ticket_code_id', $fichcode)
                            ->where([
                                ['is_cancel', '=', 0],
                                ['is_delete', '=', 0],
                                ['pending', '=', 0],

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
                        ['branch_id', '=', Session('branchId')],
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
                    return view('superviseur.secondrapport', ['bank' => $bank, 'control' => $control, 'vendeur' => $data, 'date_debut' => $request->$dateDebut, 'date_fin' => $dateFin, 'period' => 'Maten']);
                } elseif ($request->input('period') == 'soir') {


                    $userIds = DB::table('ticket_code')
                        ->where('compagnie_id', '=', $loginId)
                        ->whereBetween('ticket_code.created_at', [$dateDebut1, $dateFin1])
                        ->whereTime('ticket_code.created_at', '>', '14:30:00')
                        ->distinct()
                        ->pluck('user_id');
                    $data = collect();
                    foreach ($userIds as $user) {
                        $fichcode = DB::table('ticket_code')
                            ->where('compagnie_id', '=', $loginId)
                            ->where('user_id', '=', $user)
                            ->whereBetween('ticket_code.created_at', [$dateDebut1, $dateFin1])
                            ->whereTime('ticket_code.created_at', '>', '14:30:00')
                            ->pluck('code');

                        $result = DB::table('ticket_vendu')
                            ->whereIn('ticket_code_id', $fichcode)
                            ->where([
                                ['is_cancel', '=', 0],
                                ['is_delete', '=', 0],
                                ['pending', '=', 0],

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

                    //control historique
                    $control = DB::table('tbl_control')->where([
                        ['tbl_control.compagnie_id', '=', Session('loginId')],
                    ])->join('users', 'users.code', '=', 'tbl_control.id_user')
                        ->select('tbl_control.*', 'users.bank_name')
                        ->orderByDesc('date_rapport')
                        ->limit('50')
                        ->get();
                    return view('superviseur.secondrapport', ['bank' => $bank, 'control' => $control, 'vendeur' => $data, 'date_debut' => $dateDebut, 'date_fin' => $dateFin, 'period' => 'Swa']);
                } else {

                    $userIds = DB::table('ticket_code')
                        ->where('branch_id', '=', $loginId)
                        ->whereBetween('ticket_code.created_at', [$dateDebut1, $dateFin1])
                        ->distinct()
                        ->pluck('user_id');
                    $data = collect();
                    foreach ($userIds as $user) {
                        $fichcode = DB::table('ticket_code')
                            ->where('branch_id', '=', $loginId)
                            ->where('user_id', '=', $user)
                            ->whereBetween('ticket_code.created_at', [$dateDebut1, $dateFin1])
                            ->pluck('code');

                        $result = DB::table('ticket_vendu')
                            ->whereIn('ticket_code_id', $fichcode)
                            ->where([
                                ['is_cancel', '=', 0],
                                ['is_delete', '=', 0],
                                ['pending', '=', 0],

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
                        ['branch_id', '=', Session('branchId')],
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
                    return view('superviseur.secondrapport', ['bank' => $bank, 'control' => $control, 'vendeur' => $data, 'date_debut' => $dateDebut, 'date_fin' => $dateFin, 'period' => 'Tout']);
                }
            } else {
                $userIds = DB::table('ticket_code')
                    ->where('branch_id', '=', $loginId)
                    ->whereDate('ticket_code.created_at', '=', Carbon::now())
                    ->whereBetween('ticket_code.created_at', [Carbon::now()->format('Y-m-d').' 00:00:00', Carbon::now()->format('Y-m-d').' 23:59:59'])
                    ->distinct()
                    ->pluck('user_id');
                $data = collect();
                foreach ($userIds as $user) {
                    $fichcode = DB::table('ticket_code')
                        ->where('branch_id', '=', $loginId)
                        ->where('user_id', '=', $user)
                        ->whereBetween('ticket_code.created_at', [Carbon::now()->format('Y-m-d').' 00:00:00', Carbon::now()->format('Y-m-d').' 23:59:59'])
                        ->distinct()
                        ->pluck('code');

                    $result = DB::table('ticket_vendu')
                        ->whereIn('ticket_code_id', $fichcode)
                        ->where([
                            ['is_cancel', '=', 0],
                            ['is_delete', '=', 0],
                            ['pending', '=', 0],

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
                    ['branch_id', '=', Session('branchId')],
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
                return view('superviseur.secondrapport', ['bank' => $bank, 'control' => $control, 'vendeur' => $data, 'date_debut' => Carbon::now()->format('Y-m-d'), 'date_fin' => Carbon::now()->format('Y-m-d'), 'period' => 'Tout']);
            }
        } else {
            return view('superviseur/login');
        }
    }
    public function index_vendeur(){
        if (Session('branchId')) {
            $branchId = Session('branchId');
            $data = DB::table('users')
               // ->where('compagnie_id', '=', $loginId)
                ->where('branch_id', '=', $branchId)
                ->where('is_delete', '=', 0)
                ->get();
            return view('superviseur.list-vendeur', ['vendeur' => $data]);
        } else {
            return view('superviseur/login');
        }
    }
}
