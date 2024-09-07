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

            $today = Carbon::now();

            $ticketCodes = DB::table('ticket_code')
                ->where('branch_id', '=', Session('branchId'))
                ->whereDate('created_at', '=', $today)
                ->pluck('code');

            $data = DB::table('ticket_vendu')
                ->whereIn('ticket_code_id', $ticketCodes)
                ->where([
                    ['is_delete', '=', 0],
                    ['is_cancel', '=', 0],
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
    public function create_rapport2(Request $request)
    {

        if (Session('branchId')) {
            $loginId = Session('branchId');
            $dateDebut = $request->input('date_debut');
            $dateFin = $request->input('date_fin');

            if (!empty($dateDebut) && !empty($dateFin)) {

                if ($request->input('period') == 'matin') {
                    $userIds = DB::table('ticket_code')
                        ->where('branch_id', '=', $loginId)
                        ->whereDate('ticket_code.created_at', '>=', $dateDebut)
                        ->whereDate('ticket_code.created_at', '<=', $dateFin)
                        ->whereTime('ticket_code.created_at', '>', '14:30:00')
                        ->distinct()
                        ->pluck('user_id');
                    $data = collect();
                    foreach ($userIds as $user) {
                        $fichcode = DB::table('ticket_code')
                            ->where('branch_id', '=', $loginId)
                            ->where('user_id', '=', $user)
                            ->whereDate('ticket_code.created_at', '>=', $dateDebut)
                            ->whereDate('ticket_code.created_at', '<=', $dateFin)
                            ->whereTime('ticket_code.created_at', '<=', '14:30:00')
                            ->pluck('code');

                        $result = DB::table('ticket_vendu')
                            ->whereIn('ticket_code_id', $fichcode)
                            ->where([
                                ['is_cancel', '=', 0],
                                ['is_delete', '=', 0],
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
                        ->whereDate('ticket_code.created_at', '>=', $dateDebut)
                        ->whereDate('ticket_code.created_at', '<=', $dateFin)
                        ->whereTime('ticket_code.created_at', '>', '14:30:00')
                        ->distinct()
                        ->pluck('user_id');
                    $data = collect();
                    foreach ($userIds as $user) {
                        $fichcode = DB::table('ticket_code')
                            ->where('compagnie_id', '=', $loginId)
                            ->where('user_id', '=', $user)
                            ->whereDate('ticket_code.created_at', '>=', $dateDebut)
                            ->whereDate('ticket_code.created_at', '<=', $dateFin)
                            ->whereTime('ticket_code.created_at', '>', '14:30:00')
                            ->pluck('code');

                        $result = DB::table('ticket_vendu')
                            ->whereIn('ticket_code_id', $fichcode)
                            ->where([
                                ['is_cancel', '=', 0],
                                ['is_delete', '=', 0],
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
                        ->whereDate('ticket_code.created_at', '>=', $dateDebut)
                        ->whereDate('ticket_code.created_at', '<=', $dateFin)
                        ->distinct()
                        ->pluck('user_id');
                    $data = collect();
                    foreach ($userIds as $user) {
                        $fichcode = DB::table('ticket_code')
                            ->where('branch_id', '=', $loginId)
                            ->where('user_id', '=', $user)
                            ->whereDate('ticket_code.created_at', '>=', $dateDebut)
                            ->whereDate('ticket_code.created_at', '<=', $dateFin)
                            ->pluck('code');

                        $result = DB::table('ticket_vendu')
                            ->whereIn('ticket_code_id', $fichcode)
                            ->where([
                                ['is_cancel', '=', 0],
                                ['is_delete', '=', 0],
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
                    ->distinct()
                    ->pluck('user_id');
                $data = collect();
                foreach ($userIds as $user) {
                    $fichcode = DB::table('ticket_code')
                        ->where('branch_id', '=', $loginId)
                        ->where('user_id', '=', $user)
                        ->whereDate('ticket_code.created_at', '=', Carbon::now())
                        ->distinct()
                        ->pluck('code');

                    $result = DB::table('ticket_vendu')
                        ->whereIn('ticket_code_id', $fichcode)
                        ->where([
                            ['is_cancel', '=', 0],
                            ['is_delete', '=', 0],
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
}
