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
            $table->string('subscription_plan')->nullable(); // 'trial', 'monthly', 'yearly'
            $table->integer('max_slots')->default(50);
            $table->boolean('yearly_bonus_claimed')->default(false);
            $table->string('pending_plan')->nullable(); // 'monthly', 'yearly'
            $table->integer('pending_addon_qty')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_plan',
                'max_slots',
                'yearly_bonus_claimed',
                'pending_plan',
                'pending_addon_qty'
            ]);
        });
    }
};
