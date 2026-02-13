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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            
            // Foreign key ke booking
            $table->unsignedBigInteger('booking_id')->nullable();
            
            // Order ID dari Midtrans
            $table->string('order_id')->nullable()->unique();
            
            // Transaction ID dari Midtrans
            $table->string('transaction_id')->nullable()->unique();
            
            // Status pembayaran (pending, settlement, capture, deny, cancel, expire, etc)
            $table->string('payment_status')->default('pending');
            
            // Jumlah pembayaran
            $table->decimal('amount', 15, 2);
            
            // Metode pembayaran
            $table->string('payment_method')->nullable();
            
            // Referensi pembayaran (dari Midtrans)
            $table->string('payment_reference')->nullable();
            
            // Response dari Midtrans (JSON)
            $table->json('midtrans_response')->nullable();
            
            // Signature key untuk verifikasi
            $table->string('signature_key')->nullable();
            
            // Timestamp pembayaran
            $table->timestamp('payment_at')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Foreign key
            $table->foreign('booking_id')->references('id')->on('bokings')->onDelete('cascade');
            
            // Index
            $table->index('order_id');
            $table->index('transaction_id');
            $table->index('payment_status');
            $table->index('booking_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
