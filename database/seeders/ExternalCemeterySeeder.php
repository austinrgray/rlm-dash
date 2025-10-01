<?php

namespace Database\Seeders;

use App\Models\Cemetery\ExternalCemetery;
use Illuminate\Database\Seeder;

class ExternalCemeterySeeder extends Seeder
{
    public function run(): void
    {
        $this->randomFill();
        // $this->realisticFill();
    }

    private function randomFill(): void
    {
        ExternalCemetery::factory()
            ->count(25)
            ->create();
    }

    private function realisticFill(): void
    {
        // Future: import known cemeteries list
    }
}
