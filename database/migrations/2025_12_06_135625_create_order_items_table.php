<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->cascadeOnDelete();

            // DIBUAT NULLABLE AGAR QUICK ORDER TIDAK ERROR
            $table->foreignId('product_id')
                  ->nullable()
                  ->constrained('products')
                  ->nullOnDelete();

            $table->string('product_name'); // tetap disimpan utk Quick Order
            $table->decimal('price', 15, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('order_items');
    }
};
