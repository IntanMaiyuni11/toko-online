<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    public function check(Request $request)
    {
        $code = $request->query('code');

        // Cari voucher milik user yang sedang login dan belum terpakai
        $voucher = Voucher::where('code', $code)
                          ->where('users_id', Auth::id())
                          ->where('is_used', false)
                          ->first();

        if ($voucher) {
            return response()->json([
                'status' => 'success',
                'discount' => $voucher->discount_amount,
                'message' => 'Voucher berhasil digunakan!'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Kode voucher tidak valid atau sudah hangus.'
        ], 404);
    }
}