<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerProduct extends Model
{
    protected $fillable = [
        'customer_id',
        'product_name',
        'category',
        'unit',
        'quantity',
        'purchase_date', 
    ];

    protected $casts = [
        'purchase_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}