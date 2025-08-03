<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'type',
    'message',
    'product_id',
    'quantity',
    'product_name',
    'read',
    'snoozed_until'
];

protected $casts = [
    'read' => 'boolean',
    'snoozed_until' => 'datetime'
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}