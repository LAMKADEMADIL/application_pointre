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
        Schema::create('advance_bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained('workers')->onDelete('cascade');
            $table->decimal('amount', 8, 2);
            $table->enum('type', ['advance', 'bonus']); // تسبيق أو مكافأة
            $table->date('date');
            $table->enum('status', ['pending', 'settled'])->default('pending'); // لتتبع إن تم خصمه أو لا
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advance_bonuses');
    }
};
