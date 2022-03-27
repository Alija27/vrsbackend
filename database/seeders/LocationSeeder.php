<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Location::create([
            'name' => 'Bharatpur',
            'longitude' => '84.433333',
            'latitude' => '27.683333',
        ]);

        Location::create([
            'name' => 'Lalitpur',
            'longitude' => '85.316667',
            'latitude' => '27.666667',
        ]);
    }
}
