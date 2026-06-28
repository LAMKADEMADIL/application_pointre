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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained('workers')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_hours', 8, 2);
            $table->decimal('hourly_rate_used', 8, 2); // نحفظ السعر القديم للتاريخ
            $table->decimal('gross_amount', 10, 2); // الإجمالي
            $table->decimal('total_advances', 10, 2)->default(0); // المخصومات
            $table->decimal('net_paid', 10, 2); // الصافي الذي تم دفعه
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
