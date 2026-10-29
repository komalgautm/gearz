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
        Schema::create('webhook_results', function (Blueprint $table) {
            $table->id();
            $table->string('stockNumber', 255)->nullable();
            $table->string('dealerVinID', 255)->nullable();
            $table->text('url')->nullable();
            $table->text('thumbnailUrl')->nullable();
            $table->timestamps(); // this will add created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_results');
    }
};
