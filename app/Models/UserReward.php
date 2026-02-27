<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReward extends Model
{
    use HasFactory;

    // Menentukan nama tabel (karena Laravel biasanya mencari user_rewards)
    protected $table = 'user_rewards';

    // Kolom yang boleh diisi
    protected $fillable = [
        'users_id',
        'rewards_id',
    ];

    /**
     * Relasi ke User (Satu riwayat reward dimiliki satu user)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    /**
     * Relasi ke Reward (Satu riwayat reward merujuk ke satu jenis hadiah)
     */
    public function reward()
    {
        return $this->belongsTo(Reward::class, 'rewards_id', 'id');
    }
}