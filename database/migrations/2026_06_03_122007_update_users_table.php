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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'pharmacist'])->default('pharmacist');
            $table->enum('payment_status', ['pending', 'paid', 'rejected'])->default('pending');
            $table->string('payment_receipt')->nullable();
            $table->date('subscription_ends_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'payment_status', 'payment_receipt', 'subscription_ends_at']);
        });
    }
};

