<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'name',
        'price',
        'qty',
        'subtotal',
    ];

    // สร้างความสัมพันธ์กับ Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
