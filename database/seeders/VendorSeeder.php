<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vendor::create([
            'name'=>'Vendorname',
            'user_id'=>'2',
            'address'=>'gaindakot',
            'phone'=>'9812919812',
            'image'=>"image.png",
        ]);
        Vendor::create([
            'name'=>'Vendor',
            'user_id'=>'1',
            'address'=>'gaindakot',
            'phone'=>'9812919812',
            'image'=>"image.png",
        ]);
    }
}
