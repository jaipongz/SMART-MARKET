<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->string('merchant_id');
            $table->decimal('total_price', 10, 2);
            $table->enum('order_status', ['pending', 'confirmed', 'shipped', 'completed', 'canceled'])->default('pending');
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('product_id');
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->integer('qty');
            $table->decimal('subtotal', 10, 2);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
