<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['product_id', 'total_amount'];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}