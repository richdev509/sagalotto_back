<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class chartController extends Controller
{
    public function  superAdminPOS() {}
    public function adminSell()
    {

        $now = Carbon::now();
        $start = $now->copy()->subMonths(12)->startOfMonth();

        // Query to get the sales amounts by month
        $sales = DB::table('sales')
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('SUM(amount) as total'))
            ->whereBetween('created_at', [$start, $now])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Create arrays for labels and data
        $labels = [];
        $data = [];

        // Generate the labels for the last 12 months
        $currentMonth = $now->copy()->startOfMonth();
        for ($i = 0; $i < 12; $i++) {
            $labels[] = $currentMonth->format('M'); // Short month name (e.g., 'Jan')
            $data[$currentMonth->format('Y-m')] = 0; // Initialize data for each month
            $currentMonth->subMonth();
        }

        // Fill the data with actual sales amounts
        foreach ($sales as $sale) {
            $data[$sale->month] = $sale->total;
        }

        // Reorder the data to match the label order
        $data = array_reverse($data);

        // Create the final data array
        $finalData = [
            'labels' => $labels,
            'data' => array_values($data),
        ];
    }
}
