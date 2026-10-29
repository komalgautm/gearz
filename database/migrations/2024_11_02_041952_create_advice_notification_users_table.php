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
        Schema::create('advice_notification_users', function (Blueprint $table) {
            $table->id();
            $table->integer('notifytype')->comment('1=Advice 2=Notification');
            $table->integer('notifyID');
            $table->integer('userID');
            $table->integer('notifyread')->default(0);
            $table->integer('status')->comment('1=active 2=delete');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advice_notification_users');
    }
};
