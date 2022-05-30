<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Type::create([
            "name" => "Car",
            "image" => "images\car.jpg"
        ]);
        Type::create([
            "name" => "Bike",
            "image" => "images\bike.jpeg"
        ]);
        Type::create([
            "name" => "Scooter",
            "image" => "images\scooter.jpeg"
        ]);

        Type::create([
            "name" => "Bus",
            "image" => "images\bus.jpeg"
        ]);
        Type::create([
            "name" => "Van",
            "image" => "images\a.jpeg"
        ]);
    }
}
