<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'users_id', 'code', 'discount_amount', 'is_used'
    ];
}