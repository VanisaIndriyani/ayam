<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('cart_id')->nullable()->constrained('carts')->nullOnDelete();

            $table->string('code')->unique();

            $table->enum('status', ['pending', 'paid', 'processing', 'packed', 'shipped', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'expired'])->default('pending');

            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);

            $table->string('shipping_name');
            $table->string('shipping_phone');
            $table->text('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_postal_code')->nullable();

            $table->string('courier')->nullable();
            $table->string('service')->nullable();
            $table->integer('weight')->default(0);

            $table->string('tracking_number')->nullable();
            $table->string('tracking_url')->nullable();

            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->timestamp('ordered_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
