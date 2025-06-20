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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->nullable()->unique();
            $table->string('name');
            $table->string('slug')->nullable()->unique();
            $table->string('photo')->unique();
            $table->string('attachment_file_name')->unique();
            $table->string('brand')->nullable();
            $table->decimal('cost', 10, 2);
            $table->decimal('price', 10, 2);
            $table->enum('status', ['paused', 'active', 'inactive'])->default('paused');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->integer('stock')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
