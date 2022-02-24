<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    $this->call(UserSeeder::class);
    $this->call(VendorSeeder::class);
    $this->call(TypeSeeder::class);
    $this->call(VehicleSeeder::class);
    $this->call(RentalSeeder::class);
    }
}
