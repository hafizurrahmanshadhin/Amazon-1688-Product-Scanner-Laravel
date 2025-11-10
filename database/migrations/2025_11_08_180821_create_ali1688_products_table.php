<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('ali1688_products', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->index(); // internal 1688 id
            $table->string('title');
            $table->decimal('price_min', 10, 2)->nullable();
            $table->decimal('price_max', 10, 2)->nullable();
            $table->unsignedInteger('moq')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('supplier_url')->nullable();
            $table->string('product_url');
            $table->string('image_url')->nullable();
            $table->json('images')->nullable();
            $table->json('raw')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('ali1688_products');
    }
};
