<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'product_name',
        'category',
        'unit',
        'quantity',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
