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
        Schema::create('service_providerserviceslists', function (Blueprint $table) {
            $table->id();
            $table->integer('serviceproviderID');
            $table->integer('type')->comment("1=service provider 2=branch");
            $table->integer('serviceID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_providerserviceslists');
    }
};
