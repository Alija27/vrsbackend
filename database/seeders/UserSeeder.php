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
            'image' => 'images/8KXIGYz4n6PGmM9BD4P3.png',
            'password' => bcrypt('password'),
            'role' => 'Admin',
        ]);

        User::create([
            'name' => 'Vendor',
            'email' => 'vendor@gmail.com',
            'address' => 'gaindakot',
            'phone' => '9812919813',
            'image' => 'images/8KXIGYz4n6PGmM9BD4P3.png',
            'password' => bcrypt('password'),
            'role' => 'Vendor',
        ]);

        User::create([
            'name' => 'Customer',
            'email' => 'customer@gmail.com',
            'address' => 'gaindakot',
            'phone' => '9812919814',
            'image' => 'images/8KXIGYz4n6PGmM9BD4P3.png',
            'password' => bcrypt('password'),
            'role' => 'Customer',
        ]);
    }
}
