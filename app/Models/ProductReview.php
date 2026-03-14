<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable = ['products_id', 'users_id', 'comment', 'rating','seller_reply' ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    // TAMBAHKAN INI
    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id');
    }
}