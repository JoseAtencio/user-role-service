<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'JoosCode',
            'dni' => '12345678',
            'role_id'=> 1,
            'phone' => '+5812345678',
            'email' => 'atenciosystem@gmail.com',
            'password' => bcrypt('123456789'),
        ]);
    }
}
