<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use App\Models\UserReward;
use App\Models\PointHistory; // TAMBAHKAN INI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    public function index()
    {
        $rewards = Reward::all();
        return view('pages.rewards', [
            'rewards' => $rewards
        ]);
    }

   public function redeem(Request $request, $id)
{
    $reward = Reward::findOrFail($id);
    
    /** @var \App\Models\User $user */ // Tambahkan baris ini
    $user = Auth::user();

    // 1. Cek apakah poin user cukup
    if ($user->points < $reward->points) {
        return redirect()->back()->with('error', 'Poin kamu tidak cukup.');
    }

    $user->points -= $reward->points;
    $user->save();

        // 3. Catat di tabel kepemilikan reward
        UserReward::create([
            'users_id' => $user->id,
            'rewards_id' => $reward->id
        ]);

        // 4. CATAT RIWAYAT (MINUS)
        PointHistory::create([
            'users_id' => $user->id,
            'amount' => -$reward->points, 
            'description' => 'Tukar reward: ' . $reward->name
        ]);

        return redirect()->route('rewards')->with('success', 'Berhasil menukarkan reward!');
    }
}