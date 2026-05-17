<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'product_id',
        'customer_name',
        'phone',
        'address',
        'notes',
        'status',
        'payment_method',
        'payment_status',
        'vnpay_txn_ref',
        'total_amount'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
