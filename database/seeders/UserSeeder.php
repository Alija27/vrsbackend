<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'address' => 'gaindakot',
            'phone' => '9812919812',
            'image' => 'images/XGnc1blguyiAfapjvTWP.jpg',
            'password' => bcrypt('password'),
            'role' => 'Admin',
            'citizenship_number' => '5698',
            'citizenship_image' => 'images/XGnc1blguyiAfapjvTWP.jpg'
        ]);


        User::create([
            'name' => 'Vendor',
            'email' => 'vendor@gmail.com',
            'address' => 'gaindakot',
            'phone' => '9812919813',
            'image' => 'images/XGnc1blguyiAfapjvTWP.jpg',
            'password' => bcrypt('password'),
            'role' => 'Vendor',
            'citizenship_number' => '56988',
            'citizenship_image' => 'images/XGnc1blguyiAfapjvTWP.jpg'
        ]);

        User::create([
            'name' => 'Customer',
            'email' => 'customer@gmail.com',
            'address' => 'gaindakot',
            'phone' => '9812919814',
            'image' => 'images/XGnc1blguyiAfapjvTWP.jpg',
            'password' => bcrypt('password'),
            'role' => 'Customer',
            'citizenship_number' => '569898',
            'citizenship_image' => 'images/XGnc1blguyiAfapjvTWP.jpg'
        ]);

        User::create([
            'name' => 'A',
            'email' => 'a@gmail.com',
            'address' => 'gaindakot',
            'phone' => '9812919814',
            'image' => 'images/XGnc1blguyiAfapjvTWP.jpg',
            'password' => bcrypt('password'),
            'role' => 'Vendor',
            'citizenship_number' => '56989',
            'citizenship_image' => 'images/XGnc1blguyiAfapjvTWP.jpg'
        ]);
    }
}
