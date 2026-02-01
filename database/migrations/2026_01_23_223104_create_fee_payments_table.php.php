<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fee_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')->constrained()->cascadeOnDelete();

            $table->string('receipt_no')->unique();
            $table->decimal('paid_amount', 10, 2)->default(0);

            $table->enum('payment_mode', ['cash', 'upi', 'bank'])->default('cash');
            $table->date('paid_date');

            $table->text('note')->nullable(); // optional
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_payments');
    }
};
