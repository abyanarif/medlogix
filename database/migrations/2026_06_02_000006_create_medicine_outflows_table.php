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
        Schema::create('medicine_outflows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained('medicines')->onDelete('cascade');
            $table->integer('jumlah_keluar');
            $table->date('tanggal_keluar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_outflows');
    }
};
