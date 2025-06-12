<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID generado en frontend
            $table->string('customer_name');
            $table->string('whatsapp');
            $table->string('email')->nullable();
            $table->text('address');
            $table->enum('status', ['pending', 'confirmed', 'sent', 'delivered', 'cancelled'])->default('pending');
            $table->string('payment_type')->default('cash_on_delivery');
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
