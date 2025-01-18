<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TransactionDetail extends Pivot
{

    protected $table = 'transaction_detail';
    
    protected $guarded = ["id"];
}
