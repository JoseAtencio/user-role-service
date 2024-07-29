<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Activity;

class ActivityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Activity::create(['name' => 'Business']);
        Activity::create(['name' => 'Service']);
        Activity::create(['name' => 'Software Developer']);
        Activity::create(['name' => 'Design']);
        Activity::create(['name' => 'Food']);
        Activity::create(['name' => 'Others']);
    }
}
