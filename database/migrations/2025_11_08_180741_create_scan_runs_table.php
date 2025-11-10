<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('scan_runs', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('marketplace')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedInteger('items_found')->default(0);
            $table->json('meta')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('scan_runs');
    }
};
