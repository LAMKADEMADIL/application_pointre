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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained('workers')->onDelete('cascade');
            $table->date('date');
            $table->time('morning_entry')->nullable();
            $table->time('morning_exit')->nullable();
            $table->time('afternoon_entry')->nullable();
            $table->time('afternoon_exit')->nullable();
            $table->integer('total_minutes')->default(0); // نحفظها بالدقائق لسهولة الحساب
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
