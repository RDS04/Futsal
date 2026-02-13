<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tambahkan payment_status field ke table costumers
     * Default: 'pending' (belum bayar)
     * Values: 'pending', 'paid', 'canceled'
     */
    public function up(): void
    {
        Schema::table('costumers', function (Blueprint $table) {
            if (!Schema::hasColumn('costumers', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('costumers', function (Blueprint $table) {
            if (Schema::hasColumn('costumers', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
        });
    }
};
