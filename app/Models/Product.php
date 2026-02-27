<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 
        'users_id', 
        'categories_id', 
        'price', 
        'description', 
        'slug'
    ];

    protected $hidden = [];

    /**
     * Relasi ke ProductGallery (Satu produk punya banyak foto)
     */
    public function galleries(): HasMany
    {
        return $this->hasMany(ProductGallery::class, 'products_id', 'id');
    }

    /**
     * Relasi ke User (Pemilik produk/Penjual)
     */
    public function user(): BelongsTo
    {
        // Gunakan belongsTo karena products menyimpan users_id
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    /**
     * Relasi ke Category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categories_id', 'id');
    }
}