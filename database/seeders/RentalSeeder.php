<?php

namespace Database\Seeders;

use App\Models\Rental;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rental::create([
            "user_id" => 1,
            "vehicle_id" => 1,
            "start_date" => "2023/2/2",
            "end_date" => "2023/2/3",
            "destination" => "Langtang",
            "is_approved" => true,
            "is_complete" => true,
            "total_amount" => "20000",
            "remarks" => "i98ruwe",
        ]);
    }
}
