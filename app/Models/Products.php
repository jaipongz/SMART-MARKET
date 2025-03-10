<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id'; // ใช้ id เป็น PRIMARY KEY แทน
    public $incrementing = true;  // เปิด Auto Increment
    protected $keyType = 'int';   // กำหนดเป็น int
    protected $fillable = [
        'product_id',
        'merchant_id',
        'product_name',
        'product_pic',
        'amount',
        'price',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'amount' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
