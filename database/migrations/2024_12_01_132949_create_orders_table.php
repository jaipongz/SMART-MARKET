<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('merchant_id');  // เพิ่มคอลัมน์ merchant_id
            $table->string('order_id')->unique();
            $table->decimal('total_price', 8, 2);
            $table->timestamps();
            
            // สร้าง Foreign Key เพื่อเชื่อมโยงกับตาราง merchants (ถ้าคุณมีตาราง merchants)
            $table->foreign('merchant_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
