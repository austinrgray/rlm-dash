<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExternalCemetery;

class ExternalCemeterySeeder extends Seeder
{
    public function run(): void
    {
        ExternalCemetery::factory()
            ->count(25) // adjust as needed
            ->create();
    }
}
