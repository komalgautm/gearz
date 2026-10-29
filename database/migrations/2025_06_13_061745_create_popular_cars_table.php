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
        Schema::create('popular_cars', function (Blueprint $table) {
            $table->id();

            // Foreign key reference to brands table
            $table->unsignedBigInteger('brand_id');
            $table->foreign('brand_id')
                ->references('id')->on('brands')
                ->onUpdate('cascade')     // update on update
                ->onDelete('cascade');    // delete on delete

            $table->string('make');
            $table->string('model');
            $table->string('trim');
            $table->string('year');
            $table->boolean('position');
            $table->boolean('status');

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
        Schema::dropIfExists('popular_cars');
    }
};
