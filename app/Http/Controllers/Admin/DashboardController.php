<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
{
    $customer = User::count();
    $revenue = Transaction::sum('total_price');
    $transaction = Transaction::count();

    // Data untuk Grafik (6 Bulan Terakhir)
    $revenue_data = [];
    $label_data = [];
    for ($i = 5; $i >= 0; $i--) {
        $month = \Carbon\Carbon::now()->subMonths($i);
        $label_data[] = $month->isoFormat('MMMM');
        
        $revenue_data[] = Transaction::whereMonth('created_at', $month->month)
                                     ->whereYear('created_at', $month->year)
                                     ->sum('total_price');
    }

    return view('pages.admin.dashboard', [
        'customer' => $customer,
        'revenue' => $revenue,
        'transaction' => $transaction,
        'recent_transactions' => Transaction::with(['user'])->latest()->take(5)->get(),
        'revenue_data' => $revenue_data,
        'label_data' => $label_data
    ]);
}
}