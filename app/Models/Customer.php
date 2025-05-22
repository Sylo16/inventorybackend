<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'purchase_date'];

    protected $casts = [
        'purchase_date' => 'date',
    ];
    

    public function products()
    {
        return $this->hasMany(CustomerProduct::class);
        return $this->hasMany(Product::class);
    }
}
