<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointHistory extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'users_id',
        'amount',
        'description',
    ];

    /**
     * Relasi ke User (Satu riwayat poin dimiliki oleh satu user)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
}