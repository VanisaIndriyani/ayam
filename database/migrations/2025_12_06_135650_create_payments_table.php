<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();

            $table->string('provider')->default('midtrans');
            $table->string('payment_type')->nullable();

            $table->string('transaction_id')->nullable();
            $table->timestamp('transaction_time')->nullable();
            $table->string('transaction_status')->nullable();
            $table->string('fraud_status')->nullable();

            $table->string('va_number')->nullable();
            $table->string('bank')->nullable();

            $table->decimal('gross_amount', 15, 2);
            $table->string('currency')->default('IDR');
            $table->string('payment_url')->nullable();

            $table->json('raw_response')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('payments');
    }
};
