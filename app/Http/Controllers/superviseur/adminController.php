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
use App\Models\tirage_record;

class adminController extends Controller
{
    /**
     * List vendors for the current supervisor's branch.
     */
    public function index_vendeur()
    {
        if (!Session('branchId')) {
            return view('superviseur.login');
        }

        $vendeur = User::where([
            ['branch_id', '=', Session('branchId')],
            ['is_delete', '=', 0],
        ])->get();

        // View path used previously for supervisor area
        return view('superviseur.list-vendeur', ['vendeur' => $vendeur]);
    }

    /**
     * Edit a vendor belonging to the current supervisor's branch.
     */
    public function edit_vendeur(Request $request)
    {
        if (!Session('branchId')) {
            return view('superviseur.login');
        }

        $vendeur = User::where([
            ['branch_id', '=', Session('branchId')],
            ['id', '=', $request->input('id')],
            ['is_delete', '=', 0],
        ])->first();

        if (!$vendeur) {
            notify()->error('Gen yon ere ki pase');
            return back();
        }

        // Fetch branch (single model) for percent / configuration checks in view
        $branch = branch::find(Session('branchId'));

        return view('superviseur.edit_vendeur', [
            'vendeur' => $vendeur,
            'branch' => $branch,
        ]);
    }

    /**
     * Update vendor information. Do not update percent when percent_agent_only=1.
     * When percent_agent_only=0, clamp vendor percent to [0, branch.percent].
     */
    public function update_vendeur(Request $request)
    {
        if (!Session('branchId')) {
            return view('superviseur.login');
        }

        // Basic validation
        $validated = $request->validate([
            'id' => 'required|integer',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:30',
            'percent' => 'nullable|numeric|min:0',
            'password' => 'nullable|string|min:4',
        ]);

        $branch = branch::find(Session('branchId'));

        // Find vendor in current supervisor branch
        $vendeur = User::where([
            ['id', '=', $validated['id']],
            ['branch_id', '=', Session('branchId')],
            ['is_delete', '=', 0],
        ])->first();

        if (!$vendeur) {
            notify()->error('Gen yon ere ki pase');
            return back();
        }

        // Prepare updates
        $updateData = [
            'name' => $validated['name'],
            'address' => $validated['address'] ?? $vendeur->address,
            'gender' => $validated['gender'] ?? $vendeur->gender,
            'phone' => $validated['phone'] ?? $vendeur->phone,
            'is_block' => $request->has('block') ? '1' : '0',
        ];

        // Update password only if provided
        if (!empty($validated['password'] ?? null)) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        // Server-side enforcement for percent rules
        if ($branch && (int)$branch->percent_agent_only === 0) {
            // Allow updating percent but clamp to [0, branch.percent]
            if ($request->filled('percent')) {
                $newPercent = (float)$request->input('percent');
                $newPercent = max(0, $newPercent);
                if (isset($branch->percent)) {
                    $newPercent = min((float)$branch->percent, $newPercent);
                }
                $updateData['percent'] = $newPercent;
            }
        } // else percent_agent_only=1 => ignore any incoming percent

        foreach ($updateData as $key => $val) {
            $vendeur->{$key} = $val;
        }
        $vendeur->save();

        notify()->success('Vandè a modifye avèk siksè');
        return back();
    }

    public function admin()
    {
        if (!Session('branchId')) {
            return view('superviseur/login');
        }

        $branchId = Session('branchId');

        $dateStart = Carbon::today()->startOfDay();
        $dateEnd   = Carbon::today()->endOfDay();

        /**
         * =================================================
         * 1️⃣ DAILY TOTALS (OPTIMIZED JOIN)
         * =================================================
         */
        $data = DB::table('ticket_vendu as tv')
            ->join('ticket_code as tc', 'tc.code', '=', 'tv.ticket_code_id')
            ->where('tc.branch_id', $branchId)
            ->whereBetween('tc.created_at', [$dateStart, $dateEnd])
            ->where([
                ['tv.is_delete', 0],
                ['tv.is_cancel', 0],
                ['tv.pending', 0],
            ])
            ->selectRaw('
            COALESCE(SUM(tv.amount),0) as total_amount,
            COALESCE(SUM(tv.winning),0) as total_winning,
            COALESCE(SUM(tv.commission),0) as total_commission
        ')
            ->first();

        $vente      = $data->total_amount;
        $perte      = $data->total_winning;
        $commission = $data->total_commission;

        /**
         * =================================================
         * 2️⃣ LAST 3 DRAWS WITH FINANCIALS (SINGLE OPTIMIZED QUERY)
         * =================================================
         */
        $lista = BoulGagnant::with('tirage_record:id,name')
            ->where('compagnie_id', session('compagnieId'))
            ->latest('created_at')
            ->limit(3)
            ->get();

        $list = [];

        if ($lista->isNotEmpty()) {

            /**
             * =================================================
             * 3️⃣ BATCH FINANCIAL AGGREGATES (OPTIMIZED - NO DATE() FUNCTION)
             * Index recommendation: CREATE INDEX idx_tc_branch_date ON ticket_code(branch_id, created_at);
             * Index recommendation: CREATE INDEX idx_tv_tirage_status ON ticket_vendu(tirage_record_id, is_delete, is_cancel, pending);
             * =================================================
             */
            $tirageIds = $lista->pluck('tirage_id')->unique()->toArray();

            // Build date-tirage pairs for IN clause (more efficient than range scan)
            $dateRanges = [];
            foreach ($lista as $item) {
                $dateStart = $item->created_ . ' 00:00:00';
                $dateEnd = $item->created_ . ' 23:59:59';
                $dateRanges[$item->created_] = [$dateStart, $dateEnd];
            }

            // Aggregate by building CASE statements for each date
            $selectCases = [];
            $groupKeys = [];
            foreach ($lista as $item) {
                $date = $item->created_;
                $tirageId = $item->tirage_id;
                $key = "{$date}|{$tirageId}";
                $groupKeys[$key] = ['date' => $date, 'tirage' => $tirageId];
            }

            // Single query with conditional aggregation (avoids DATE() function)
            $financialData = [];
            foreach ($lista as $boulGagnant) {
                $dateStart = $boulGagnant->created_ . ' 00:00:00';
                $dateEnd = $boulGagnant->created_ . ' 23:59:59';
                
                $result = DB::table('ticket_vendu as tv')
                    ->join('ticket_code as tc', 'tc.code', '=', 'tv.ticket_code_id')
                    ->where('tc.branch_id', $branchId)
                    ->whereBetween('tc.created_at', [$dateStart, $dateEnd])
                    ->where('tv.tirage_record_id', $boulGagnant->tirage_id)
                    ->where('tv.is_delete', 0)
                    ->where('tv.is_cancel', 0)
                    ->where('tv.pending', 0)
                    ->selectRaw('
                        COALESCE(SUM(tv.amount), 0) as vent,
                        COALESCE(SUM(tv.winning), 0) as pert,
                        COALESCE(SUM(tv.commission), 0) as commissio
                    ')
                    ->first();

                $list[] = [
                    'boulGagnant' => $boulGagnant,
                    'vent'       => $result->vent ?? 0,
                    'pert'       => $result->pert ?? 0,
                    'commissio'  => $result->commissio ?? 0,
                    'name'       => $boulGagnant->tirage_record->name ?? '',
                ];
            }
        }

        return view('superviseur.admin', [
            'vente'      => $vente,
            'perte'      => $perte,
            'commission' => $commission,
            'list'       => $list,
        ]);
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

                return redirect('/superviseur/login');
            }
        }
    }

    /**
     * Calculate branch commission based on percent_agent_only mode
     * @param object $branchData Branch model instance
     * @param float $vente Total sales amount
     * @param float $userPercent User commission percentage (default 0)
     * @return float Branch commission amount
     */
    private function calculateBranchCommission($branchData, $vente, $userPercent = 0)
    {
        // Defensive: ensure we have a branch model instance
        if (!$branchData || !is_object($branchData)) {
            return 0.0;
        }
        if ($branchData->percent_agent_only == 0) {
            // Differential mode: branch gets difference between branch% and user%
            $diffPercent = max(0, $branchData->percent - $userPercent);
            $branchCommission = ($vente * $diffPercent) / 100;
        } else {
            // Direct mode: branch gets full branch% from sales
            $branchCommission = ($vente * $branchData->percent) / 100;
        }
        return round($branchCommission, 2);
    }

    public function create_rapport(Request $request)
    {
        if (Session('branchId')) {
            if (!empty($request->input('date_debut') && !empty($request->input('date_fin')))) {
                $date_debut = $request->input('date_debut');
                $date_fin = $request->input('date_fin');
                $date_debut1 = $date_debut . ' 00:00:00';
                $date_fin1 = $date_fin . ' 23:59:59';

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
                        return view('superviseur.rapport', ['vente' => $vente, 'perte' => $perte, 'ticket_win' => $ticket_win, 'ticket_lose' => $ticket_lose, 'ticket_paid' => $ticket_peye, 'date_debut' => $request->input('date_debut'), 'date_fin' => $request->input('date_fin'), 'bank' => 'Tout', 'tirage_' => 'Tout', 'is_calculated' => 1, 'vendeur' => $user, 'tirage' => $tirage, 'commission' => $commission, 'branch' => $branch, 'branch_' => 'Tout']);
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
                    // Branch-specific reports with commission calculation
                    $branchId = $request->input('branch');
                    $branchData = branch::find($branchId);

                    if ($request->input('bank') == 'Tout' && $request->input('tirage') == 'Tout') {

                        $ticketCodes = DB::table('ticket_code')
                            ->where('branch_id', '=', $branchId)
                            ->whereBetween('created_at', [$date_debut1, $date_fin1])
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

                        $vente = $result->total_vente ?? 0;
                        $perte = $result->total_perte ?? 0;
                        $commission = $result->total_commission ?? 0;
                        $ticket_win = $result->total_ticket_win ?? 0;
                        $ticket_lose = $result->total_ticket_lose ?? 0;
                        $ticket_peye = $result->total_ticket_paye ?? 0;

                        // Get per-user sales for branch commission calculation
                        $userVentes = DB::table('ticket_code')
                            ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                            ->join('users', 'users.id', '=', 'ticket_code.user_id')
                            ->where('ticket_code.branch_id', $branchId)
                            ->whereBetween('ticket_code.created_at', [$date_debut1, $date_fin1])
                            ->where('ticket_vendu.is_cancel', 0)
                            ->where('ticket_vendu.is_delete', 0)
                            ->where('ticket_vendu.pending', 0)
                            ->groupBy('ticket_code.user_id', 'users.percent')
                            ->select('ticket_code.user_id', 'users.percent as user_percent', DB::raw('SUM(ticket_vendu.amount) as user_vente'))
                            ->get();

                        // Safely accumulate branch commission only if branchData is a valid model instance
                        $branchCommission = 0;
                        if ($branchData) {
                            foreach ($userVentes as $uv) {
                                $branchCommission += $this->calculateBranchCommission($branchData, $uv->user_vente, $uv->user_percent);
                            }
                        }

                        $netBalance = $vente - $perte - $commission - $branchCommission;


                        //get vendeur
                        $user = User::where([
                            ['branch_id', '=', Session('branchId')]
                        ])->select('users.id', 'users.name', 'users.bank_name')
                            ->get();
                        //get tirage
                        $company = branch::where('id', '=', Session('branchId'))
                            ->select('compagnie_id')
                            ->first();
                        $tirage = tirage_record::where([
                            ['compagnie_id', '=', $company->compagnie_id]

                        ])->select('tirage_record.id', 'tirage_record.name')
                            ->get();
                        //get branch
                        $branch = branch::where([
                            ['id', '=', Session('branchId')]
                        ])->get();
                        $name_branch = branch::where([
                            ['id', '=', $branchId]
                        ])->select('name')->first();

                        return view('superviseur.rapport', [
                            'vente' => $vente,
                            'perte' => $perte,
                            'ticket_win' => $ticket_win,
                            'ticket_lose' => $ticket_lose,
                            'ticket_paid' => $ticket_peye,
                            'date_debut' => $request->input('date_debut'),
                            'date_fin' => $request->input('date_fin'),
                            'bank' => 'Tout',
                            'tirage_' => 'Tout',
                            'is_calculated' => 1,
                            'vendeur' => $user,
                            'tirage' => $tirage,
                            'commission' => $commission,
                            'branch' => $branch,
                            'branch_' => $name_branch->name,
                            'branch_commission' => round($branchCommission, 2),
                            'net_balance' => round($netBalance, 2)
                        ]);
                    } elseif ($request->input('bank') == 'Tout' && $request->input('tirage') != 'Tout') {
                        $ticketCodes = DB::table('ticket_code')
                            ->where('branch_id', '=', $request->input('branch'))
                            ->whereBetween('created_at', [$date_debut1, $date_fin1])
                            ->pluck('code');

                        // Then, use the list of ticket codes to query ticket_vendu
                        $result = DB::table('ticket_vendu')
                            ->whereIn('ticket_code_id', $ticketCodes)
                            ->where([
                                ['is_cancel', '=', 0],
                                ['is_delete', '=', 0],
                                ['pending', '=', 0],
                                ['tirage_record_id', '=', $request->input('tirage')]
                            ])
                            ->select(
                                DB::raw('SUM(amount) as total_vente'),
                                DB::raw('SUM(winning) as total_perte'),
                                DB::raw('SUM(commission) as total_commission'),
                                DB::raw('COUNT(CASE WHEN is_win = 1 THEN 1 END) as total_ticket_win'),
                                DB::raw('COUNT(CASE WHEN is_win = 0 THEN 1 END) as total_ticket_lose'),
                                DB::raw('COUNT(CASE WHEN is_payed = 1 THEN 1 END) as total_ticket_paye')
                            )->first();

                        $vente = $result->total_vente ?? 0;
                        $perte = $result->total_perte ?? 0;
                        $commission = $result->total_commission ?? 0;
                        $ticket_win = $result->total_ticket_win ?? 0;
                        $ticket_lose = $result->total_ticket_lose ?? 0;
                        $ticket_peye = $result->total_ticket_paye ?? 0;

                        // Get per-user sales for branch commission calculation
                        $userVentes = DB::table('ticket_code')
                            ->join('ticket_vendu', 'ticket_vendu.ticket_code_id', '=', 'ticket_code.code')
                            ->join('users', 'users.id', '=', 'ticket_code.user_id')
                            ->where('ticket_code.branch_id', $branchId)
                            ->where('ticket_vendu.tirage_record_id', $request->input('tirage'))
                            ->whereBetween('ticket_code.created_at', [$date_debut1, $date_fin1])
                            ->where('ticket_vendu.is_cancel', 0)
                            ->where('ticket_vendu.is_delete', 0)
                            ->where('ticket_vendu.pending', 0)
                            ->groupBy('ticket_code.user_id', 'users.percent')
                            ->select('ticket_code.user_id', 'users.percent as user_percent', DB::raw('SUM(ticket_vendu.amount) as user_vente'))
                            ->get();

                        // Safely accumulate branch commission only if branchData is a valid model instance
                        $branchCommission = 0;
                        if ($branchData) {
                            foreach ($userVentes as $uv) {
                                $branchCommission += $this->calculateBranchCommission($branchData, $uv->user_vente, $uv->user_percent);
                            }
                        }

                        $netBalance = $vente - $perte - $commission - $branchCommission;

                        //get vendeur
                        $user = User::where([
                            ['branch_id', '=', Session('branchId')]
                        ])->select('users.id', 'users.name', 'users.bank_name')
                            ->get();
                        //get tirage
                        $company = branch::where('id', '=', Session('branchId'))
                            ->select('compagnie_id')
                            ->first();
                        $tirage = tirage_record::where([
                            ['compagnie_id', '=', $company->compagnie_id]
                        ])->select('tirage_record.id', 'tirage_record.name')
                            ->get();
                        //get tirage name
                        $name_tirage = tirage_record::where([
                            ['id', '=', $request->input('tirage')]
                        ])->select('name')->first();
                        //get branch
                        $branch = branch::where([
                            ['id', '=', Session('branchId')]
                        ])->get();
                        $name_branch = branch::where([
                            ['id', '=', $branchId]
                        ])->select('name')->first();

                        return view('superviseur.rapport', [
                            'vente' => $vente,
                            'perte' => $perte,
                            'ticket_win' => $ticket_win,
                            'ticket_lose' => $ticket_lose,
                            'ticket_paid' => $ticket_peye,
                            'date_debut' => $request->input('date_debut'),
                            'date_fin' => $request->input('date_fin'),
                            'bank' => 'Tout',
                            'tirage_' => $name_tirage->name,
                            'is_calculated' => 1,
                            'vendeur' => $user,
                            'tirage' => $tirage,
                            'commission' => $commission,
                            'branch' => $branch,
                            'branch_' => $name_branch->name,
                            'branch_commission' => round($branchCommission, 2),
                            'net_balance' => round($netBalance, 2)
                        ]);
                    } elseif ($request->input('bank') != 'Tout' && $request->input('tirage') == 'Tout') {

                        $ticketCodes = DB::table('ticket_code')
                            ->where('branch_id', '=', $request->input('branch'))
                            ->where('user_id', '=', $request->input('bank'))
                            ->whereBetween('created_at', [$date_debut1, $date_fin1])
                            ->pluck('code');

                        // Then, use the list of ticket codes to query ticket_vendu
                        $result = DB::table('ticket_vendu')
                            ->whereIn('ticket_code_id', $ticketCodes)
                            ->where([
                                ['is_cancel', '=', 0],
                                ['is_delete', '=', 0],
                                ['pending', '=', 0],
                            ])
                            ->select(
                                DB::raw('SUM(amount) as total_vente'),
                                DB::raw('SUM(winning) as total_perte'),
                                DB::raw('SUM(commission) as total_commission'),
                                DB::raw('COUNT(CASE WHEN is_win = 1 THEN 1 END) as total_ticket_win'),
                                DB::raw('COUNT(CASE WHEN is_win = 0 THEN 1 END) as total_ticket_lose'),
                                DB::raw('COUNT(CASE WHEN is_payed = 1 THEN 1 END) as total_ticket_paye')
                            )->first();

                        $vente = $result->total_vente ?? 0;
                        $perte = $result->total_perte ?? 0;
                        $commission = $result->total_commission ?? 0;
                        $ticket_win = $result->total_ticket_win ?? 0;
                        $ticket_lose = $result->total_ticket_lose ?? 0;
                        $ticket_peye = $result->total_ticket_paye ?? 0;

                        // Get user percent for specific bank
                        $selectedUser = User::find($request->input('bank'));
                        $userPercent = $selectedUser ? $selectedUser->percent : 0;
                        $branchCommission = $this->calculateBranchCommission($branchData, $vente, $userPercent);
                        $netBalance = $vente - $perte - $commission - $branchCommission;

                        //get vendeur
                        $user = User::where([
                            ['branch_id', '=', Session('branchId')]
                        ])->select('users.id', 'users.name', 'users.bank_name')
                            ->get();
                        //get tirage name
                        $name_bank = User::where('id', '=', $request->input('bank'))->select('bank_name')->first();
                        //get tirage
                        $company = branch::where('id', '=', Session('branchId'))
                            ->select('compagnie_id')
                            ->first();
                        $tirage = tirage_record::where([
                            ['compagnie_id', '=', $company->compagnie_id]
                        ])->select('tirage_record.id', 'tirage_record.name')
                            ->get();
                        //get branch
                        $branch = branch::where([
                            ['id', '=', Session('branchId')]
                        ])->get();
                        $name_branch = branch::where([
                            ['id', '=', $branchId]
                        ])->select('name')->first();

                        return view('superviseur.rapport', [
                            'vente' => $vente,
                            'perte' => $perte,
                            'ticket_win' => $ticket_win,
                            'ticket_lose' => $ticket_lose,
                            'ticket_paid' => $ticket_peye,
                            'date_debut' => $request->input('date_debut'),
                            'date_fin' => $request->input('date_fin'),
                            'bank' => $name_bank->bank_name,
                            'tirage_' => 'Tout',
                            'is_calculated' => 1,
                            'vendeur' => $user,
                            'tirage' => $tirage,
                            'commission' => $commission,
                            'branch' => $branch,
                            'branch_' => $name_branch->name,
                            'branch_commission' => round($branchCommission, 2),
                            'net_balance' => round($netBalance, 2)
                        ]);
                    } elseif ($request->input('bank') != 'Tout' && $request->input('tirage') != 'Tout') {
                        $ticketCodes = DB::table('ticket_code')
                            ->where('branch_id', '=', $request->input('branch'))
                            ->where('user_id', '=', $request->input('bank'))
                            ->whereBetween('created_at', [$date_debut1, $date_fin1])
                            ->pluck('code');

                        // Then, use the list of ticket codes to query ticket_vendu
                        $result = DB::table('ticket_vendu')
                            ->whereIn('ticket_code_id', $ticketCodes)
                            ->where([
                                ['is_cancel', '=', 0],
                                ['tirage_record_id', '=', $request->input('tirage')],
                                ['is_delete', '=', 0],
                                ['pending', '=', 0],
                            ])
                            ->select(
                                DB::raw('SUM(amount) as total_vente'),
                                DB::raw('SUM(winning) as total_perte'),
                                DB::raw('SUM(commission) as total_commission'),
                                DB::raw('COUNT(CASE WHEN is_win = 1 THEN 1 END) as total_ticket_win'),
                                DB::raw('COUNT(CASE WHEN is_win = 0 THEN 1 END) as total_ticket_lose'),
                                DB::raw('COUNT(CASE WHEN is_payed = 1 THEN 1 END) as total_ticket_paye')
                            )->first();

                        $vente = $result->total_vente ?? 0;
                        $perte = $result->total_perte ?? 0;
                        $commission = $result->total_commission ?? 0;
                        $ticket_win = $result->total_ticket_win ?? 0;
                        $ticket_lose = $result->total_ticket_lose ?? 0;
                        $ticket_peye = $result->total_ticket_paye ?? 0;

                        // Get user percent for specific bank
                        $selectedUser = User::find($request->input('bank'));
                        $userPercent = $selectedUser ? $selectedUser->percent : 0;
                        $branchCommission = $this->calculateBranchCommission($branchData, $vente, $userPercent);
                        $netBalance = $vente - $perte - $commission - $branchCommission;

                        //get vendeur
                        $user = User::where([
                            ['branch_id', '=', Session('branchId')]
                        ])->select('users.id', 'users.name', 'users.bank_name')
                            ->get();
                        //get tirage name
                        $tirage_name = tirage_record::where([
                            ['id', '=', $request->input('tirage')]
                        ])->select('name')->first();
                        $name_bank = User::where('id', '=', $request->input('bank'))->select('bank_name')->first();
                        //get tirage
                        $company = branch::where('id', '=', Session('branchId'))
                            ->select('compagnie_id')
                            ->first();
                        $tirage = tirage_record::where([
                            ['compagnie_id', '=', $company->compagnie_id]
                        ])->select('tirage_record.id', 'tirage_record.name')
                            ->get();
                        //get branch
                        $branch = branch::where([
                            ['id', '=', Session('branchId')]
                        ])->get();
                        $name_branch = branch::where([
                            ['id', '=', $branchId]
                        ])->select('name')->first();

                        return view('superviseur.rapport', [
                            'vente' => $vente,
                            'perte' => $perte,
                            'ticket_win' => $ticket_win,
                            'ticket_lose' => $ticket_lose,
                            'ticket_paid' => $ticket_peye,
                            'date_debut' => $request->input('date_debut'),
                            'date_fin' => $request->input('date_fin'),
                            'bank' => $name_bank->bank_name,
                            'tirage_' => $tirage_name->name,
                            'is_calculated' => 1,
                            'vendeur' => $user,
                            'tirage' => $tirage,
                            'commission' => $commission,
                            'branch' => $branch,
                            'branch_' => $name_branch->name,
                            'branch_commission' => round($branchCommission, 2),
                            'net_balance' => round($netBalance, 2)
                        ]);
                    }
                }
            } else {
                //get vendeur
                $user = User::where([
                    ['branch_id', '=', Session('branchId')]

                ])->select('users.id', 'users.name', 'users.bank_name')
                    ->get();
                //get tirage
                $company = branch::where('id', '=', Session('branchId'))
                    ->select('compagnie_id')
                    ->first();
                $tirage = tirage_record::where([
                    ['compagnie_id', '=', $company->compagnie_id]

                ])->select('tirage_record.id', 'tirage_record.name')
                    ->get();
                $branch = Branch::where([
                    ['id', '=', Session('branchId')],
                    ['is_delete', '=', 0],
                ])->get();
                return view('superviseur.rapport', ['vendeur' => $user, 'tirage' => $tirage, 'is_calculated' => 0, 'branch' => $branch]);
            }
        } else {
            return view('superviseur.login');
        }
    }
    public function create_rapport2(Request $request)
    {
        // Ensure branch session exists
        if (!Session('branchId')) {
            return view('superviseur.login');
        }

        $loginId = Session('branchId');
        $dateDebut = $request->input('date_debut');
        $dateFin = $request->input('date_fin');

        // If dates are not provided, default to today
        if (empty($dateDebut) || empty($dateFin)) {
            $dateDebut = Carbon::now()->format('Y-m-d');
            $dateFin = $dateDebut;
        }

        $dateDebut1 = $dateDebut . ' 00:00:00';
        $dateFin1 = $dateFin . ' 23:59:59';

        $period = $request->input('period'); // 'matin', 'soir' or other
        $data = collect();

        // Get branch data for commission calculations
        $branchData = \App\Models\branch::find($loginId);

        // Build base query for user IDs
        $userIdsQuery = DB::table('ticket_code')
            ->where('branch_id', '=', $loginId)
            ->whereBetween('ticket_code.created_at', [$dateDebut1, $dateFin1]);

        if ($period == 'matin') {
            $userIdsQuery = $userIdsQuery->whereTime('ticket_code.created_at', '<=', '14:30:00');
        } elseif ($period == 'soir') {
            $userIdsQuery = $userIdsQuery->whereTime('ticket_code.created_at', '>', '14:30:00');
        }

        $userIds = $userIdsQuery->distinct()->orderBy('user_id')->pluck('user_id');

        foreach ($userIds as $userId) {
            // Build ticket_code query for this user and period
            $fichQuery = DB::table('ticket_code')
                ->where('branch_id', '=', $loginId)
                ->where('user_id', '=', $userId)
                ->whereBetween('ticket_code.created_at', [$dateDebut1, $dateFin1]);

            if ($period == 'matin') {
                $fichQuery = $fichQuery->whereTime('ticket_code.created_at', '<=', '14:30:00');
            } elseif ($period == 'soir') {
                $fichQuery = $fichQuery->whereTime('ticket_code.created_at', '>', '14:30:00');
            }

            $fichcode = $fichQuery->pluck('code');

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
                    DB::raw('SUM(ticket_vendu.commission) as commission')
                )
                ->first();

            $vente = $result->vente ?? 0;
            $perte = $result->perte ?? 0;
            $commission = $result->commission ?? 0;

            // Calculate superviseur commission for this user
            $userModel = \App\Models\User::find($userId);
            $userPercent = $userModel ? $userModel->percent : 0;
            $superviseurCommission = 0;
            if ($branchData) {
                if ($branchData->percent_agent_only == 0) {
                    $diffPercent = max(0, $branchData->percent - $userPercent);
                    $superviseurCommission = ($vente * $diffPercent) / 100;
                } else {
                    $superviseurCommission = ($vente * $branchData->percent) / 100;
                }
            }

            $data->push([
                'bank_name' => $userModel ? $userModel->bank_name : $userId,
                'vente' => $vente,
                'perte' => $perte,
                'commission' => $commission,
                'superviseur_commission' => round($superviseurCommission, 2)
            ]);
        }

        $bank = \App\Models\User::where([
            ['branch_id', '=', $loginId],
            ['is_delete', '=', 0],
        ])->get();

        $control = DB::table('tbl_control')->where([
            ['tbl_control.compagnie_id', '=', Session('loginId')],
        ])->join('users', 'users.code', '=', 'tbl_control.id_user')
            ->select('tbl_control.*', 'users.bank_name')
            ->orderByDesc('date_rapport')
            ->limit(50)
            ->get();

        $periodLabel = $period == 'matin' ? 'Maten' : ($period == 'soir' ? 'Swa' : 'Tout');

        return view('superviseur.secondrapport', [
            'bank' => $bank,
            'control' => $control,
            'vendeur' => $data,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'period' => $periodLabel
        ]);
    }
}
