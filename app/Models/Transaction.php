<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = ["id"];

    public function items() {
        return $this->belongsToMany(Item::class, 'transaction_detail')->withPivot(['quantity', 'subtotal'])->withTimestamps();
    }
}
