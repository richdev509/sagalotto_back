<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Models\User;
use App\Models\BoulGagnant;


class Dashstat extends Component
{
    public $vente = 0;
    public $perte = 0;
    public $commission = 0;
    public $actif_user = 0;
    public $total_user = 0;
    public $ticket_win = 0;
    public $ticket_total = 0;
    public $ticket_delete = 0;
    public $loading = true;
    public $list = []; // ← INITIALISEZ AVEC UN TABLEAU VIDE
    public $list_loading = true;

    public $dates = [];
    public $ventes = [];
    public $pertes = [];


    public function mount()
    {
        $this->loadData();
        $this->loadChartData();
        $this->loadListData();
    }

    public function loadData()
    {
        try {
            $this->loading = true;

            //Check if user is logged in
            if (!Session::has('loginId')) {
                return view('login');
            }

            $today = Carbon::now()->format('Y-m-d');
            $today1 = $today . ' 00:00:00';
            $today2 = $today . ' 23:59:59';
            $compagnieId = Session::get('loginId');

            // Batch process: Get all required data in parallel
            $results = DB::transaction(function () use ($compagnieId, $today1, $today2) {
                // 1. Get ticket codes and user data in one query
                $ticketData = DB::table('ticket_code')
                    ->where('compagnie_id', $compagnieId)
                    ->whereBetween('created_at', [$today1, $today2])
                    ->select('code', 'user_id')
                    ->get();

                $ticketCodes = $ticketData->pluck('code');
                $userActif = $ticketData->isNotEmpty() ? $ticketData->pluck('user_id')->unique()->count() : 0;

                // 2. Get ticket sales data and deleted tickets in parallel using subqueries
                $salesData = DB::table('ticket_vendu')
                    ->whereIn('ticket_code_id', $ticketCodes)
                    ->where([
                        ['is_delete', 0],
                        ['is_cancel', 0],
                        ['pending', 0],
                    ])
                    ->selectRaw('
                COALESCE(SUM(amount), 0) as total_amount, 
                COALESCE(SUM(winning), 0) as total_winning, 
                COALESCE(SUM(commission), 0) as total_commission,
                COALESCE(COUNT(CASE WHEN is_win > 0 THEN 1 END), 0) as winning_count,
                COALESCE(COUNT(*), 0) as ticket_total
            ')->first();

                $ticketDelete = $ticketCodes->isNotEmpty() ? DB::table('ticket_vendu')
                    ->whereIn('ticket_code_id', $ticketCodes)
                    ->where(function ($query) {
                        $query->where('is_delete', 1)
                            ->orWhere('is_cancel', 1);
                    })
                    ->count() : 0;

                // 3. Get total users count
                $userTotal = User::where([
                    ['compagnie_id', $compagnieId],
                    ['is_delete', 0]
                ])->count();

                return [
                    'salesData' => $salesData,
                    'userActif' => $userActif,
                    'ticketDelete' => $ticketDelete,
                    'userTotal' => $userTotal,
                ];
            });

            $this->vente = $results['salesData']->total_amount;
            $this->perte = $results['salesData']->total_winning;
            $this->commission = $results['salesData']->total_commission;
            $this->actif_user = $results['userActif'];
            $this->total_user = $results['userTotal'];
            $this->ticket_win = $results['salesData']->winning_count;
            $this->ticket_total = $results['salesData']->ticket_total;
            $this->ticket_delete = $results['ticketDelete'];
        } finally {
            $this->loading = false;
        }
    }

    // Load list data separately
    public function loadListData()
    {
        $this->list_loading = true;
        $compagnieId = Session::get('loginId');
        $lista = \App\Models\BoulGagnant::where('compagnie_id', $compagnieId)
            ->latest('created_at')
            ->take(3)
            ->with('tirage_record')
            ->get();
        $list = [];
        if ($lista->isNotEmpty()) {
            foreach ($lista as $boulGagnant) {
                $date = $boulGagnant->created_;
                $codes = DB::table('ticket_code')
                    ->where('compagnie_id', $compagnieId)
                    ->whereBetween('created_at', [$date. ' 00:00:00', $date . ' 23:59:59'])
                    ->pluck('code');
                if ($codes->isNotEmpty()) {
                    $financialData = DB::table('ticket_vendu')
                        ->whereIn('ticket_code_id', $codes)
                        ->where('tirage_record_id', $boulGagnant->tirage_id)
                        ->where('is_delete', 0)
                        ->where('is_cancel', 0)
                        ->where('pending', 0)
                        ->selectRaw('
                            COALESCE(SUM(amount), 0) as vent,
                            COALESCE(SUM(winning), 0) as pert,
                            COALESCE(SUM(commission), 0) as commissio
                        ')->first();
                } else {
                    $financialData = (object)[
                        'vent' => 0,
                        'pert' => 0,
                        'commissio' => 0
                    ];
                }
                $list[] = [
                    'boulGagnant' => $boulGagnant,
                    'vent' => $financialData->vent,
                    'pert' => $financialData->pert,
                    'commissio' => $financialData->commissio,
                    'name' => $boulGagnant->tirage_record->name ?? 'Unknown'
                ];
            }
        }
        $this->list = $list;
        $this->list_loading = false;
    }

    public function refreshData()
    {
        $this->loadData();
        $this->loadChartData();
        $this->loadListData();
    }

    // Load last 7 days vente/perte for chart
    public function loadChartData()
    {
        $compagnieId = Session::get('loginId');
        $cacheKey = 'chart_data_' . $compagnieId;
        $cached = Cache::get($cacheKey);
        if ($cached) {
            $this->dates = $cached['dates'];
            $this->ventes = $cached['ventes'];
            $this->pertes = $cached['pertes'];
            // Debug temporaire
           // \Log::info('ChartData (cache)', ['dates' => $this->dates, 'ventes' => $this->ventes, 'pertes' => $this->pertes]);
            return;
        }
        $dates = [];
        $ventes = [];
        $pertes = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dates[] = $date;

            $codes = DB::table('ticket_code')
                ->where('compagnie_id', $compagnieId)
                ->whereBetween('created_at',[$date. ' 00:00:00', $date . ' 23:59:59'])
                ->pluck('code');

            // On récupère la somme pour CE jour uniquement
            $ven = 0;
            $per = 0;
            if ($codes->count() > 0) {
                $ven = DB::table('ticket_vendu')
                    ->whereIn('ticket_code_id', $codes)
                    ->where('is_delete', 0)
                    ->where('is_cancel', 0)
                    ->where('pending', 0)
                    ->sum('amount');

                $per = DB::table('ticket_vendu')
                    ->whereIn('ticket_code_id', $codes)
                    ->where('is_delete', 0)
                    ->where('is_cancel', 0)
                    ->where('pending', 0)
                    ->sum('winning');
            }
            $ventes[] = $ven;
            $pertes[] = $per;
        }
        $this->dates = $dates;
        $this->ventes = $ventes;
        $this->pertes = $pertes;
        // Debug temporaire
       // \Log::info('ChartData', ['dates' => $dates, 'ventes' => $ventes, 'pertes' => $pertes]);
        Cache::put($cacheKey, [
            'dates' => $dates,
            'ventes' => $ventes,
            'pertes' => $pertes
        ], now()->addHours(12));
    }

    // Méthode pour charger les stats via wire:init
    public function loadStatData()
    {
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.dashstat', [
            'dates' => $this->dates,
            'ventes' => $this->ventes,
            'pertes' => $this->pertes,
            'list_loading' => $this->list_loading,
        ]);
    }
}
