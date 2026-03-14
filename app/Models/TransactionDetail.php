<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $fillable = [
        'transactions_id', 
        'products_id',
        'price',
        'shipping_status',
        'resi',
        'code'
    ];

    protected $hidden = [

    ];

    // PERBAIKAN: Gunakan belongsTo untuk relasi ke tabel induk (Product)
    public function product(){
        return $this->belongsTo( Product::class, 'products_id', 'id' );
    }

    // PERBAIKAN: Gunakan belongsTo untuk relasi ke tabel induk (Transaction)
    public function transaction(){
        return $this->belongsTo( Transaction::class, 'transactions_id', 'id' );
    }
}