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
            "name" => "Car"
        ]);
        Type::create([
            "name" => "Bike"
        ]);
        Type::create([
            "name" => "Bus"
        ]);
        Type::create([
            "name" => "Van"
        ]);
        Type::create([
            "name" => "Bolero"
        ]);
    }
}
