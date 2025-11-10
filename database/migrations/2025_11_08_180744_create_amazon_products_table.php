<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('amazon_products', function (Blueprint $table) {
            $table->id();
            $table->string('asin')->unique();
            $table->string('marketplace', 4);
            $table->string('title');
            $table->string('category')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->float('rating')->nullable();
            $table->unsignedInteger('reviews')->nullable();
            $table->string('image_url')->nullable();
            $table->json('images')->nullable();
            $table->json('raw')->nullable();
            $table->timestamps();
            $table->index(['marketplace', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('amazon_products');
    }
};
