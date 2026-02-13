<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;

class ActivateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:activate {name : Nama admin yang ingin diaktifkan}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aktifkan akun admin yang telah dinon-aktifkan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        
        $admin = Admin::where('name', $name)->first();
        
        if (!$admin) {
            $this->error("Admin dengan nama '$name' tidak ditemukan!");
            return 1;
        }
        
        if ($admin->is_active) {
            $this->info("Admin '$name' sudah aktif.");
            return 0;
        }
        
        $admin->update(['is_active' => true]);
        
        $this->info("✓ Admin '$name' berhasil diaktifkan!");
        return 0;
    }
}
