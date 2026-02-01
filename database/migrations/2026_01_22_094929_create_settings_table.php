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
        Schema::create('settings', function (Blueprint $table) {
             $table->id();

        // School Info
        $table->string('school_name')->nullable();
        $table->string('school_phone')->nullable();
        $table->string('school_email')->nullable();
        $table->text('school_address')->nullable();

        // Logo
        $table->string('school_logo')->nullable(); // store path

        // Receipt
        $table->string('receipt_prefix')->default('REC-');
        $table->text('receipt_footer')->nullable();

        // App settings
        $table->string('currency_symbol')->default('₹');
        $table->string('session_year')->nullable(); // e.g 2025-26

        // Backup settings
        $table->integer('backup_retention')->default(30); // keep last 30 backups

        $table->timestamps();
    });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
