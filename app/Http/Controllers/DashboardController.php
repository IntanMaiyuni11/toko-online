<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\TransactionDetail;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        
        $transactions = TransactionDetail::with(['transaction.user','product.galleries'])
                        ->whereHas('product', function($product){
                            $product->where('users_id', Auth::user()->id);
                        });

        // Gunakan get() untuk mengambil data sebelum melakukan reduce
        $revenue = $transactions->get()->reduce(function ($carry, $item){
            return $carry + $item->price;
        }, 0); 

        $customer = User::count();

        return view('pages.dashboard',[
            'transaction_count' => $transactions->count(),
            'transaction_data' => $transactions->get(),
            'revenue' => $revenue,
            'customer' => $customer
        ]);
    }
}