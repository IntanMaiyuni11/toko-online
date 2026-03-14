<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use App\Models\UserReward;
use App\Models\PointHistory; 
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RewardController extends Controller
{
   public function index()
{
    $user = Auth::user();

    // Ambil data agar variabel $rewards di blade ADA isinya
    $rewards = \App\Models\Reward::latest()->get();

    // Ambil riwayat agar variabel $histories di blade ADA isinya
    $histories = \App\Models\PointHistory::where('users_id', $user->id)->latest()->get();

    return view('pages.rewards', [
        'rewards' => $rewards,
        'histories' => $histories
    ]);
}

   public function redeem(Request $request, $id)
{
    $reward = Reward::findOrFail($id);
    /** @var \App\Models\User $user */
    $user = Auth::user();

    // Pastikan poin cukup (Berlaku untuk USER & CUSTOMER)
    if ($user->points < $reward->points) {
        return redirect()->back()->with('error', 'Poin kamu tidak cukup.');
    }

    // Proses potong poin
    $user->decrement('points', $reward->points);

    // Catat riwayat
    PointHistory::create([
        'users_id' => $user->id,
        'amount' => -$reward->points, 
        'description' => 'Tukar reward: ' . $reward->name
    ]);

    // Buat Voucher yang bisa dipakai belanja
    Voucher::create([
        'users_id' => $user->id,
        'code' => 'VO-' . strtoupper(Str::random(6)),
        'discount_amount' => $reward->discount_amount,
        'is_used' => false
    ]);

    return redirect()->route('dashboard-vouchers')->with('success', 'Berhasil! Voucher belanja kamu sudah siap.');
}

    public function vouchers()
    {
        $vouchers = Voucher::where('users_id', Auth::id())->latest()->get();
        return view('pages.dashboard-vouchers', compact('vouchers'));
    }
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'points' => 'required|integer|min:1',
        'discount_amount' => 'required|integer|min:0',
    ]);

    $data = $request->all();
    $data['users_id'] = Auth::id(); // PENTING: Mengunci reward ke ID Seller yang login

    Reward::create($data);

    return redirect()->route('rewards')->with('success', 'Reward Toko berhasil ditambahkan!');
}
// Tambahkan fungsi-fungsi ini di dalam RewardController

public function create()
{
    return view('pages.rewards-create');
}

public function edit($id)
{
    // Cek apakah data ada di database dan milik user yang sedang login
    $item = Reward::where('users_id', Auth::id())->find($id);

    // Debugging: Jika item tidak ketemu, jangan langsung findOrFail
    if (!$item) {
        // Coba ganti ke ini sementara untuk cek apakah ID-nya memang tidak ada
        return "Data reward dengan ID $id tidak ditemukan atau bukan milik Anda.";
    }

    return view('pages.rewards-edit', compact('item'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'points' => 'required|integer|min:1',
        'discount_amount' => 'required|integer|min:0',
    ]);

    $item = Reward::where('users_id', Auth::id())->findOrFail($id);
    $item->update($request->all());

    return redirect()->route('rewards')->with('success', 'Reward berhasil diperbarui!');
}

public function destroy($id)
{
    $item = Reward::where('users_id', Auth::id())->findOrFail($id);
    $item->delete();

    return redirect()->route('rewards')->with('success', 'Reward berhasil dihapus!');
}
}