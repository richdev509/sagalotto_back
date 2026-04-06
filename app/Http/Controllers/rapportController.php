<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\tirage_record;
use Exception;
use App\Models\branch;
use Illuminate\Contracts\Session\Session;
use Carbon\Carbon;

class rapportController extends Controller
{
    /**
     * Calculate branch commission based on percent_agent_only rule:
     * - If percent_agent_only = 0: branch gets (branch.percent - user.percent) * vente
     * - If percent_agent_only = 1: branch gets branch.percent * vente
     * 
     * @param object $branchData Branch record with percent, percent_agent_only
     * @param float $vente Total sales amount
     * @param float $userPercent User's commission percentage
     * @return float branch commission amount
     */
    private function calculateBranchCommission($branchData, $vente, $userPercent = 0)
    {
        $branchCommission = 0;

        if ($branchData->percent_agent_only == 0) {
            // Differential: branch takes difference between branch% and user%
            $diffPercent = max(0, $branchData->percent - $userPercent);
            $branchCommission = ($vente * $diffPercent) / 100;
        } else {
            // Direct: branch takes full branch% from sales
            $branchCommission = ($vente * $branchData->percent) / 100;
        }

        return round($branchCommission, 2);
    }
    public function create_rapport(Request $request)
    {
        if (!session('loginId')) {
            return view('login');
        }

        $compagnieId = session('loginId');

        // Load filters (for view)
        $vendeurs = User::where('compagnie_id', $compagnieId)
            ->select('id', 'name', 'bank_name', 'percent')
            ->get();

        $tirages = tirage_record::where('compagnie_id', $compagnieId)
            ->select('id', 'name')
            ->get();

        $branches = Branch::where('compagnie_id', $compagnieId)
            ->where('is_delete', 0)
            ->get();

        // No date → just show page
        if (!$request->filled(['date_debut', 'date_fin'])) {
            return view('rapport', [
                'vendeur' => $vendeurs,
                'tirage' => $tirages,
                'branch' => $branches,
                'is_calculated' => 0
            ]);
        }

        $dateDebut = $request->date_debut . ' 00:00:00';
        $dateFin   = $request->date_fin . ' 23:59:59';

        /**
         * ===============================
         * SINGLE MASTER QUERY - FETCHES EVERYTHING AT ONCE
         * ===============================
         */
        $cacheKey = 'rapport_complete:' . md5(json_encode([
            $compagnieId,
            $dateDebut,
            $dateFin,
            $request->bank,
            $request->tirage,
            $request->branch
        ]));

        $results = Cache::remember($cacheKey, 300, function () use ($compagnieId, $dateDebut, $dateFin, $request) {

            // Build the SELECT part based on branch filter
            $selectRaw = '
            COALESCE(SUM(tv.amount), 0) as total_vente,
            COALESCE(SUM(tv.winning), 0) as total_perte,
            COALESCE(SUM(tv.commission), 0) as total_commission,
            SUM(CASE WHEN tv.is_win = 1 THEN 1 ELSE 0 END) as total_ticket_win,
            SUM(CASE WHEN tv.is_win = 0 THEN 1 ELSE 0 END) as total_ticket_lose,
            SUM(CASE WHEN tv.is_payed = 1 THEN 1 ELSE 0 END) as total_ticket_paye';

            // Add branch commission calculation only if branch is selected
            if ($request->branch !== 'Tout' && $request->branch) {
                $selectRaw .= ',
            COALESCE(SUM(
                CASE 
                    WHEN b.percent_agent_only = 0
                    THEN tv.amount * (GREATEST(0, b.percent - u.percent) / 100)
                    ELSE tv.amount * (b.percent / 100)
                END
            ), 0) as total_branch_commission';
            } else {
                $selectRaw .= ', 0 as total_branch_commission';
            }

            // Base query
            $query = DB::table('ticket_code as tc')
                ->join('ticket_vendu as tv', 'tv.ticket_code_id', '=', 'tc.code')
                ->join('users as u', 'u.id', '=', 'tc.user_id')
                ->where('tc.compagnie_id', $compagnieId)
                ->whereBetween('tc.created_at', [$dateDebut, $dateFin])
                ->where('tv.is_cancel', 0)
                ->where('tv.is_delete', 0)
                ->where('tv.pending', 0);

            // Left join branches only if needed for commission calculation
            if ($request->branch !== 'Tout' && $request->branch) {
                $query->leftJoin('branches as b', function ($join) use ($request) {
                    $join->on('b.id', '=', 'tc.branch_id')
                        ->where('b.id', '=', $request->branch);
                });
            }

            // Apply filters
            if ($request->bank !== 'Tout' && $request->bank) {
                $query->where('tc.user_id', $request->bank);
            }

            if ($request->tirage !== 'Tout' && $request->tirage) {
                $query->where('tv.tirage_record_id', $request->tirage);
            }

            if ($request->branch !== 'Tout' && $request->branch) {
                $query->where('tc.branch_id', $request->branch);
            }

            return $query->selectRaw($selectRaw)->first();
        });

        // Extract values (all calculated in SQL already)
        $totalVente = $results->total_vente ?? 0;
        $totalPerte = $results->total_perte ?? 0;
        $totalCommission = $results->total_commission ?? 0;
        $branchCommission = $results->total_branch_commission ?? 0;

        // Net balance calculation
        $netBalance = $totalVente - $totalPerte - $totalCommission - $branchCommission;

        /**
         * ===============================
         * DISPLAY NAMES
         * ===============================
         */
        $bankName = ($request->bank === 'Tout' || !$request->bank)
            ? 'Tout'
            : (optional($vendeurs->where('id', $request->bank)->first())->bank_name ?? 'Inconnu');

        $tirageName = ($request->tirage === 'Tout' || !$request->tirage)
            ? 'Tout'
            : (optional($tirages->where('id', $request->tirage)->first())->name ?? 'Inconnu');

        $branchName = ($request->branch === 'Tout' || !$request->branch)
            ? 'Tout'
            : (optional($branches->where('id', $request->branch)->first())->name ?? 'Inconnu');

        /**
         * ===============================
         * RETURN VIEW
         * ===============================
         */
        return view('rapport', [
            'vente' => round($totalVente, 2),
            'perte' => round($totalPerte, 2),
            'commission' => round($totalCommission, 2),
            'ticket_win' => $results->total_ticket_win ?? 0,
            'ticket_lose' => $results->total_ticket_lose ?? 0,
            'ticket_paid' => $results->total_ticket_paye ?? 0,
            'branch_commission' => round($branchCommission, 2),
            'net_balance' => round($netBalance, 2),
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'bank' => $bankName,
            'tirage_' => $tirageName,
            'branch_' => $branchName,
            'vendeur' => $vendeurs,
            'tirage' => $tirages,
            'branch' => $branches,
            'is_calculated' => 1
        ]);
    }

    public function create_rapport2(Request $request)
    {
        if (!session('loginId')) {
            return view('login');
        }

        $loginId = session('loginId');

        // Get date range or default to today
        $dateDebut = $request->input('date_debut') ?? now()->format('Y-m-d');
        $dateFin   = $request->input('date_fin')   ?? now()->format('Y-m-d');

        $dateStart = $dateDebut . ' 00:00:00';
        $dateEnd   = $dateFin . ' 23:59:59';

        // Treat null branch as "tout"
        $branchId = $request->input('branch') ?? 'tout';

        $data = collect();

        if ($branchId !== 'tout') {
            $branchData = Branch::find($branchId);

            $userIds = DB::table('ticket_code')
                ->where('compagnie_id', $loginId)
                ->where('branch_id', $branchId)
                ->whereBetween('created_at', [$dateStart, $dateEnd])
                ->distinct()
                ->orderBy('user_id')
                ->pluck('user_id');

            foreach ($userIds->chunk(100) as $userChunk) {
                foreach ($userChunk as $userId) {
                    $codes = DB::table('ticket_code')
                        ->where('compagnie_id', $loginId)
                        ->where('branch_id', $branchId)
                        ->where('user_id', $userId)
                        ->whereBetween('created_at', [$dateStart, $dateEnd])
                        ->pluck('code');

                    if ($codes->isEmpty()) continue;

                    $result = DB::table('ticket_vendu')
                        ->whereIn('ticket_code_id', $codes)
                        ->where([
                            ['is_cancel', 0],
                            ['is_delete', 0],
                            ['pending', 0]
                        ])
                        ->select(
                            DB::raw('COALESCE(SUM(amount),0) as vente'),
                            DB::raw('COALESCE(SUM(winning),0) as perte'),
                            DB::raw('COALESCE(SUM(commission),0) as commission')
                        )
                        ->first();

                    $userPercent = User::find($userId)?->percent ?? 0;
                    $branchCommission = $this->calculateBranchCommission($branchData, $result->vente, $userPercent);

                    $data->push([
                        'bank_name' => $userId,
                        'vente' => $result->vente,
                        'perte' => $result->perte,
                        'commission' => $result->commission,
                        'branch_commission' => $branchCommission
                    ]);
                }
            }
        } else {
            // All branches
            $userIds = DB::table('ticket_code')
                ->where('compagnie_id', $loginId)
                ->whereBetween('created_at', [$dateStart, $dateEnd])
                ->distinct()
                ->orderBy('user_id')
                ->pluck('user_id');

            foreach ($userIds->chunk(100) as $userChunk) {
                foreach ($userChunk as $userId) {
                    $codes = DB::table('ticket_code')
                        ->where('compagnie_id', $loginId)
                        ->where('user_id', $userId)
                        ->whereBetween('created_at', [$dateStart, $dateEnd])
                        ->pluck('code');

                    if ($codes->isEmpty()) continue;

                    $result = DB::table('ticket_vendu')
                        ->whereIn('ticket_code_id', $codes)
                        ->where([
                            ['is_cancel', 0],
                            ['is_delete', 0],
                            ['pending', 0]
                        ])
                        ->select(
                            DB::raw('COALESCE(SUM(amount),0) as vente'),
                            DB::raw('COALESCE(SUM(winning),0) as perte'),
                            DB::raw('COALESCE(SUM(commission),0) as commission')
                        )
                        ->first();

                    $data->push([
                        'bank_name' => $userId,
                        'vente' => $result->vente,
                        'perte' => $result->perte,
                        'commission' => $result->commission
                    ]);
                }
            }
        }

        $bank = User::where('compagnie_id', $loginId)
            ->where('is_delete', 0)
            ->get();

        $branchList = Branch::where('compagnie_id', $loginId)
            ->where('is_delete', 0)
            ->get();

        $control = DB::table('tbl_control')
            ->join('users', 'users.code', '=', 'tbl_control.id_user')
            ->where('tbl_control.compagnie_id', $loginId)
            ->select('tbl_control.*', 'users.bank_name')
            ->orderByDesc('date_rapport')
            ->limit(50)
            ->get();

        return view('raportsecond', [
            'bank' => $bank,
            'control' => $control,
            'vendeur' => $data,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'branch' => $branchList,
            'show_branch_commission' => ($branchId !== 'tout'),
            'branch_name' => $branchId !== 'tout' ? $branchList->firstWhere('id', $branchId)?->name : null
        ]);
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
                ->whereBetween('ticket_code.created_at', [$date_debut . ' 00:00:00', $date_fin . ' 23:59:59'])
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
