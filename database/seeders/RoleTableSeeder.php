<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name'=>'Owner','description'=>'Owner GOOD rank']);
        Role::create(['name'=>'Admin','description'=>'Admin  CEO rank']);
        Role::create(['name'=>'Assistant','description'=>'Assistant Supervisor rank']);
        Role::create(['name'=>'Guest','description'=>'Guest Guest rank']);
    }
}
