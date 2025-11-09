<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DashboardStat extends Component
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

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        try {
            $this->loading = true;
            
            // Code simplifié pour tester
            $compagnieId = Session::get('loginId', 1);
            
            // Données factices pour tester - remplacez par votre vraie logique après
            $this->vente = DB::table('ticket_vendu')
                ->where('is_delete', 0)
                ->where('is_cancel', 0)
                ->whereDate('created_at', today())
                ->sum('amount') ?? 12540.00;

            $this->perte = DB::table('ticket_vendu')
                ->where('is_delete', 0)
                ->where('is_cancel', 0)
                ->whereDate('created_at', today())
                ->sum('winning') ?? 3240.50;

            $this->actif_user = DB::table('ticket_code')
                ->whereDate('created_at', today())
                ->distinct('user_id')
                ->count('user_id') ?? 243;

            $this->total_user = \App\Models\User::where('is_delete', 0)->count() ?? 1542;

        } catch (\Exception $e) {
            // En cas d'erreur, utiliser des valeurs par défaut
            $this->vente = 12540.00;
            $this->perte = 3240.50;
            $this->commission = 1254.00;
            $this->actif_user = 243;
            $this->total_user = 1542;
            $this->ticket_win = 45;
            $this->ticket_total = 1245;
            $this->ticket_delete = 23;
            
            logger()->error('Erreur AdminStats: ' . $e->getMessage());
        } finally {
            $this->loading = false;
        }
    }

    public function refreshData()
    {
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.admin-stats');
    }
}