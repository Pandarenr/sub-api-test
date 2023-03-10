<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RubricSeeder extends Seeder
{
    public function run()
    {
        \App\Model\Rubric::factory(5)->create();
    }
}