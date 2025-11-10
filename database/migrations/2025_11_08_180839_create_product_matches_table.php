<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('product_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('amazon_product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ali1688_product_id')->constrained()->cascadeOnDelete();
            $table->float('similarity');
            $table->boolean('already_on_amazon')->default(false);
            $table->decimal('amazon_price', 10, 2)->nullable();
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->decimal('estimated_margin', 10, 2)->nullable();
            $table->string('status')->default('candidate');
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->unique(['amazon_product_id', 'ali1688_product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('product_matches');
    }
};
