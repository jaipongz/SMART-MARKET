<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'merchant_id',
        'total_price',
        'order_status',
    ];

    // สร้างความสัมพันธ์กับ OrderItem
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
