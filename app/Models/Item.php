<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        "category_id",
        "name",
        "price",
        "quantity",
        "description"

    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_detail')->withPivot(['quantity', 'subtotal'])->withTimestamps();
    }
}
