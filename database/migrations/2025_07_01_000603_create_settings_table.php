<?php

// database/migrations/xxxx_xx_xx_create_settings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->text('description')->nullable();
            $table->string('location')->nullable(); // Dirección
            $table->string('whatsapp_contact')->nullable();
            $table->decimal('shipping_price', 10, 2)->nullable();
            $table->json('business_hours')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('settings');
    }
};
