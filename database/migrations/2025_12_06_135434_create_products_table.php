<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            
            // relasi ke kategori
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained('categories')
                  ->nullOnDelete();

            // data dasar produk
            $table->string('name');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();

            // harga dan stok
            $table->decimal('price', 15, 2);
            $table->integer('stock')->default(0);
            $table->integer('weight')->default(0); // dalam gram

            // 🔥 DITAMBAH agar seeder produk tidak error
            $table->integer('sold_count')->default(0);

            // 🔥 DITAMBAH untuk menampung thumbnail / cover image
            $table->string('image')->nullable();

            // status produk
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('products');
    }
};
