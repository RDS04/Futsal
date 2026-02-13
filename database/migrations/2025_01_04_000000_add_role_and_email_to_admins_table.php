<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Menambahkan:
     * - role: master (CEO/Owner) atau regional (admin per region)
     * - email: untuk kontak admin
     * - is_active: status admin
     * 
     * Migration ini berjalan SETELAH create_admins_table.php
     * Jadi hanya alter table saja.
     */
    public function up(): void
    {
        // Cek apakah tabel admins sudah ada
        if (Schema::hasTable('admins')) {
            Schema::table('admins', function (Blueprint $table) {
                // Tambah kolom email (nullable dulu, nanti bisa di-set manually)
                if (!Schema::hasColumn('admins', 'email')) {
                    $table->string('email')->nullable()->unique()->after('name');
                }
                
                // Tambah kolom role setelah region
                if (!Schema::hasColumn('admins', 'role')) {
                    $table->enum('role', ['master', 'regional'])
                          ->default('regional')
                          ->after('region');
                }
                
                // Tambah kolom status untuk active/inactive admin
                if (!Schema::hasColumn('admins', 'is_active')) {
                    $table->boolean('is_active')
                          ->default(true)
                          ->after('role');
                }
            });
            
            // Tambah index setelah kolom ada
            Schema::table('admins', function (Blueprint $table) {
                try {
                    $table->index('role');
                } catch (\Exception $e) {
                    // Index mungkin sudah ada
                }
                try {
                    $table->index('region');
                } catch (\Exception $e) {
                    // Index mungkin sudah ada
                }
                try {
                    $table->index('is_active');
                } catch (\Exception $e) {
                    // Index mungkin sudah ada
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['region']);
            $table->dropIndex(['is_active']);
            
            if (Schema::hasColumn('admins', 'email')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('admins', 'role')) {
                $table->dropColumn('role');
            }
            if (Schema::hasColumn('admins', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
