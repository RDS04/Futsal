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
        Schema::create('bokings', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->nullable()->unique();
            $table->string('nama');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('region');
            $table->enum('status', ['pending', 'confirmed', 'canceled', 'paid'])->default('pending');
            $table->string('lapangan');
            $table->unsignedBigInteger('lapangan_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->decimal('total_harga', 10, 2)->nullable();
            $table->decimal('harga_per_jam', 10, 2)->nullable();
            $table->decimal('durasi', 5, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('lapangan_id')->references('id')->on('lapangans')->onDelete('set null');
            $table->foreign('customer_id')->references('id')->on('costumers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bokings');
    }
};
