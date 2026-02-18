<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'customer_name',
        'product',
        'qty',
        'price',
        'sale_date'
    ];

    protected $casts = [
        'sale_date' => 'date',
        'qty' => 'integer',
        'price' => 'decimal:2',
    ];

    public function getGrandTotalAttribute()
    {
        return $this->qty * $this->price;
    }
}
