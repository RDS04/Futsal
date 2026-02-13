<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{

    public function run(): void
    {
        // ==========================================
        // MASTER ADMIN - Bisa lihat semua region
        // ==========================================
        Admin::firstOrCreate(
            ['email' => 'admin@master.com'],
            [
                'name' => 'Master Admin',
                'email' => 'admin@master.com',
                'password' => 'master123', // Plain text
                'region' => 'bypass', // default region, tapi bisa akses semua
                'role' => 'master',
                'is_active' => true,
            ]
        );

        Admin::firstOrCreate(
            ['email' => 'admin@bypass.com'],
            [
                'name' => 'Admin Bypass',
                'email' => 'admin@bypass.com',
                'password' => 'bypass123', // Plain text
                'region' => 'bypass',
                'role' => 'regional',
                'is_active' => true,
            ]
        );

        // ==========================================
        // ADMIN REGION ULAK KARANG
        // ==========================================
        Admin::firstOrCreate(
            ['email' => 'admin@ulakkarang.com'],
            [
                'name' => 'Admin Ulak Karang',
                'email' => 'admin@ulakkarang.com',
                'password' => 'ulakkarang123', // Plain text
                'region' => 'ulakkarang',
                'role' => 'regional',
                'is_active' => true,
            ]
        );

        // ==========================================
        // ADMIN REGION BATU SANGKAR
        // ==========================================
        Admin::firstOrCreate(
            ['email' => 'admin@batusangkar.com'],
            [
                'name' => 'Admin Batu Sangkar',
                'email' => 'admin@batusangkar.com',
                'password' => 'batusangkar123', // Plain text
                'region' => 'batusangkar',
                'role' => 'regional',
                'is_active' => true,
            ]
        );
    }
}
