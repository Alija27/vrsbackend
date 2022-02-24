<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vehicle::create([
            'vendor_id'=>1,
            'name'=>"abc",
            'type_id'=>1,
            'model'=>"xyz",
            'color'=>"red",
            'total_seats'=>"4",
            'rental_price'=>"2000",
            'description'=>"kadak",
            'terms'=>"kakdsalk",
            'image'=>"image.png",
            'condition'=>"thik",
            'is_available'=>true,
            'has_driver'=>true,
            'is_approved'=>true,
        ]);
    }
}
