<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDiscussion extends Model
{
    protected $fillable = ['products_id', 'users_id', 'comment', 'parent_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    public function replies()
    {
        return $this->hasMany(ProductDiscussion::class, 'parent_id', 'id');
    }
}
