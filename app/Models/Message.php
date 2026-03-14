<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id', 
        'receiver_id', 
        'message', 
        'image', 
        'products_id', 
        'transactions_id', 
        'is_read'
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'products_id');
    }

    public function transaction() {
        return $this->belongsTo(Transaction::class, 'transactions_id');
    }

    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }
}