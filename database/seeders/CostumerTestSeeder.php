<?php

namespace Database\Seeders;

use App\Models\Costumers;
use Illuminate\Database\Seeder;

class CostumerTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Customer 1 - untuk bypass region
        Costumers::create([
            'name' => 'Ahmad Ridho',
            'gender' => 'L',
            'email' => 'ahmad@email.com',
            'phone' => '082255667788',
            'address' => 'Jl. Merdeka 789',
            'password' => 'ahmad123',
        ]);

        // Customer 2 - untuk ulak karang region
        Costumers::create([
            'name' => 'Siti Nurhaliza',
            'gender' => 'P',
            'email' => 'siti@email.com',
            'phone' => '082244556677',
            'address' => 'Jl. Wijaya 456',
            'password' => 'siti123',
        ]);

        // Customer 3 - untuk batu sangkar region
        Costumers::create([
            'name' => 'Budi Setiawan',
            'gender' => 'L',
            'email' => 'budi@email.com',
            'phone' => '082233445566',
            'address' => 'Jl. Pendaki 123',
            'password' => 'budi123',
        ]);

        // Customer 4 - untuk bypass region (paid user example)
        Costumers::create([
            'name' => 'Rini Wijaya',
            'gender' => 'P',
            'email' => 'rini@email.com',
            'phone' => '082266778899',
            'address' => 'Jl. Sudirman 012',
            'password' => 'rini123',
        ]);
    }
}
