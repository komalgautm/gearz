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
        Schema::create('branch_working_days', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branchId');
            $table->string('day');
            $table->string('first_shift_start_time')->nullable();
            $table->string('first_shift_end_time')->nullable();
            $table->string('second_shift_start_time')->nullable();
            $table->string('second_shift_end_time')->nullable();
            $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('branchId')->references('id')->on('agent_branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_working_days');
    }
};
