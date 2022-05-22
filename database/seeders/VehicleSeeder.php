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
            'vendor_id' => 1,
            'name' => "Audi TT",
            'type_id' => 1,
            'brand' => "Audi",
            'model' => "TT",
            'color' => "red",
            'total_seats' => "4",
            'rental_price' => "2000",
            'description' => "TT RS delivers sports car handling, muscle-car performance, and a high-end look and feel. Unlike rivals that use turbocharged four-cylinder engines or big-block V-8's, the TT RS is powered by a unique turbocharged five-cylinder engine that pumps out 394 horsepower",
            'terms' => "kadak",
            'image' => "images/Audi-TT-RS.jpg",
            'condition' => "Good",
            'is_available' => true,
            'has_driver' => true,
            'is_approved' => true,
            'location_id' => 1,
        ]);
        Vehicle::create([
            'vendor_id' => 1,
            'name' => "Audi R8",
            'type_id' => 1,
            'brand' => "Audi",
            'model' => "R8",
            'color' => "red",
            'total_seats' => "4",
            'rental_price' => "2000",
            'description' => "TT RS delivers sports car handling, muscle-car performance, and a high-end look and feel. Unlike rivals that use turbocharged four-cylinder engines or big-block V-8's, the TT RS is powered by a unique turbocharged five-cylinder engine that pumps out 394 horsepower",
            'terms' => "Kadak",
            'image' => "images/Audi R8.jfif",
            'condition' => "Good",
            'is_available' => true,
            'has_driver' => true,
            'is_approved' => true,
            'location_id' => 2,
        ]);

        Vehicle::create([
            'vendor_id' => 1,
            'name' => "Pulsar",
            'type_id' => 2,
            'brand' => "Bajaj",
            'model' => "fghj",
            'color' => "red",
            'total_seats' => "2",
            'rental_price' => "2000",
            'description' => "Bajaj 200 is a very good bike",
            'terms' => "Kadak",
            'image' => "images/bajaj-pulsar-200.jpg",
            'condition' => "Good",
            'is_available' => true,
            'has_driver' => true,
            'is_approved' => true,
            'location_id' => 4,
        ]);

        Vehicle::create([
            'vendor_id' => 1,
            'name' => "Vespa",
            'type_id' => 3,
            'brand' => "Vespa",
            'model' => "kjslk",
            'color' => "white",
            'total_seats' => "2",
            'rental_price' => "2000",
            'description' => "Bajaj 200 is a very good bike",
            'terms' => "Kadak",
            'image' => "images/v.jpg",
            'condition' => "Good",
            'is_available' => true,
            'has_driver' => true,
            'is_approved' => true,
            'location_id' => 5,
        ]);

        Vehicle::create([
            'vendor_id' => 1,
            'name' => "Bus",
            'type_id' => 4,
            'brand' => "Bus",
            'model' => "bus 200",
            'color' => "Green",
            'total_seats' => "4",
            'rental_price' => "2000",
            'description' => "TT RS delivers sports car handling, muscle-car performance, and a high-end look and feel. Unlike rivals that use turbocharged four-cylinder engines or big-block V-8's, the TT RS is powered by a unique turbocharged five-cylinder engine that pumps out 394 horsepower",
            'terms' => "Kadak",
            'image' => "images/bus.jpg",
            'condition' => "Good",
            'is_available' => true,
            'has_driver' => true,
            'is_approved' => true,
            'location_id' => 2,
        ]);

        Vehicle::create([
            'vendor_id' => 1,
            'name' => "Van",
            'type_id' => "5",
            'brand' => "Van",
            'model' => "Van 200",
            'color' => "White",
            'total_seats' => "4",
            'rental_price' => "2000",
            'description' => "TT RS delivers sports car handling, muscle-car performance, and a high-end look and feel. Unlike rivals that use turbocharged four-cylinder engines or big-block V-8's, the TT RS is powered by a unique turbocharged five-cylinder engine that pumps out 394 horsepower",
            'terms' => "Kadak",
            'image' => "images/van.jpg",
            'condition' => "Good",
            'is_available' => true,
            'has_driver' => true,
            'is_approved' => true,
            'location_id' => 2,
        ]);
    }
}
