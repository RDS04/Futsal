<?php

namespace App\Console\Commands;

use App\Models\Costumers;
use App\Models\Boking;
use Illuminate\Console\Command;

class SyncPaymentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:payment-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi payment_status customer dari data booking yang sudah dibayar';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai sinkronisasi payment status...');

        // Get semua customer
        $customers = Costumers::all();
        $syncedCount = 0;

        foreach ($customers as $customer) {
            // Check apakah customer punya booking yang paid
            $hasPaidBooking = $customer->bokings()
                ->where('status', 'paid')
                ->exists();

            // Jika punya booking paid dan payment_status belum 'paid', update ke 'paid'
            if ($hasPaidBooking && $customer->payment_status !== 'paid') {
                $customer->update(['payment_status' => 'paid']);
                $syncedCount++;
                $this->line("✓ Updated customer {$customer->name} (ID: {$customer->id})");
            }
        }

        $this->info("Selesai! Total customer yang di-sync: {$syncedCount}");
    }
}
