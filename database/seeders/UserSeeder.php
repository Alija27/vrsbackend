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
            'name'=>'Admin',
            'email'=>'admin@gmail.com',
            'address'=>'gaindakot',
            'phone'=>'9812919812',
            'password'=>bcrypt('password'),
            'role'=>'admin',
        ]);
    
        User::create([
            'name'=>'Vendor',
            'email'=>'vendor@gmail.com',
            'address'=>'gaindakot',
            'phone'=>'9812919813',
            'password'=>bcrypt('password'),
            'role'=>'vendor',
        ]);
    
        User::create([
            'name'=>'Customer',
            'email'=>'customer@gmail.com',
            'address'=>'gaindakot',
            'phone'=>'9812919814',
            'password'=>bcrypt('password'),
            'role'=>'customer',
        ]);
    }
}
