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
        Schema::create('service_icons', function (Blueprint $table) {
            $table->id();
            $table->string('icon');
            $table->string('name_en');
            $table->string('name_ar');
            $table->boolean('status')->default(1);

            // Separate timestamps
            $table->timestamp('created_at')->useCurrent();           // set current on insert
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate(); // set current on update
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_icons');
    }
};
