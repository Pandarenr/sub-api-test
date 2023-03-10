<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rubric;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Rubric::factory(5)->create();
    }
}