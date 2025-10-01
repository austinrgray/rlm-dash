<?php

namespace Database\Seeders;

use App\Models\Cemetery\InternalCemetery;
use App\Models\Cemetery\Section;
use App\Models\Cemetery\Lot;
use App\Models\Cemetery\Plot;
use App\Models\Cemetery\Mausoleum\Mausoleum;
use App\Models\Cemetery\Mausoleum\Crypt;
use App\Models\Cemetery\Columbarium\Columbarium;
use App\Models\Cemetery\Columbarium\Niche;
use Illuminate\Database\Seeder;

class RLMGSeeder extends Seeder
{
    public function run(): void
    {
        $this->randomFill();
        // $this->realisticFill();
    }

    private function randomFill(): void
    {
        $cemetery = InternalCemetery::firstOrCreate([
            'name' => 'Rose Lawn Memorial Gardens',
        ]);

        // Garden sections with lots/plots
        $this->seedSection($cemetery, 'Savior Garden', 1, 400);
        $this->seedSection($cemetery, 'Prayer Garden', 401, 600);
        $this->seedSection($cemetery, 'Stone Garden', 601, 800);
        $this->seedSection($cemetery, 'Devotion Garden', 801, 1200);
        $this->seedSection($cemetery, 'Serenity Garden', 1201, 1500);

        // Structures
        $this->seedMausoleum($cemetery, 'M-01', 42);
        $this->seedColumbarium($cemetery, 'C-01', 150);
    }

    private function realisticFill(): void
    {
        // Future: import real-world data
    }

    private function seedSection(InternalCemetery $cemetery, string $name, int $start, int $end): void
    {
        $section = Section::firstOrCreate([
            'internal_cemetery_id' => $cemetery->id,
            'name'                 => $name,
        ]);

        foreach (range($start, $end) as $lotNumber) {
            if (mt_rand(1, 100) <= mt_rand(5, 10)) {
                $this->seedSplitLot($cemetery, $section, $lotNumber);
            } else {
                $this->seedStandardLot($cemetery, $section, $lotNumber);
            }
        }
    }

    private function seedStandardLot(InternalCemetery $cemetery, Section $section, int $lotNumber): void
    {
        $lot = Lot::factory()->create([
            'internal_cemetery_id' => $cemetery->id,
            'section_id'           => $section->id,
            'lot_number'           => $lotNumber,
        ]);

        $plotCount = max(1, 6 + random_int(-1, 1));

        foreach (range(1, $plotCount) as $plotNumber) {
            Plot::factory()->create([
                'internal_cemetery_id' => $cemetery->id,
                'section_id'           => $section->id,
                'lot_id'               => $lot->id,
                'plot_number'          => $plotNumber,
                'traditional_capacity' => 1,
                'cremation_capacity'   => 1,
            ]);
        }
    }

    private function seedSplitLot(InternalCemetery $cemetery, Section $section, int $lotNumber): void
    {
        $totalPlots = max(2, 6 + random_int(-1, 1));
        $plotsForA  = random_int(1, $totalPlots - 1);
        $plotsForB  = $totalPlots - $plotsForA;

        $lotA = Lot::factory()->create([
            'internal_cemetery_id' => $cemetery->id,
            'section_id'           => $section->id,
            'lot_number'           => $lotNumber,
            'lot_letter'           => 'A',
        ]);

        foreach (range(1, $plotsForA) as $plotNumber) {
            Plot::factory()->create([
                'internal_cemetery_id' => $cemetery->id,
                'section_id'           => $section->id,
                'lot_id'               => $lotA->id,
                'plot_number'          => $plotNumber,
                'traditional_capacity' => 1,
                'cremation_capacity'   => 1,
            ]);
        }

        $lotB = Lot::factory()->create([
            'internal_cemetery_id' => $cemetery->id,
            'section_id'           => $section->id,
            'lot_number'           => $lotNumber,
            'lot_letter'           => 'B',
        ]);

        foreach (range(1, $plotsForB) as $plotNumber) {
            Plot::factory()->create([
                'internal_cemetery_id' => $cemetery->id,
                'section_id'           => $section->id,
                'lot_id'               => $lotB->id,
                'plot_number'          => $plotNumber,
                'traditional_capacity' => 1,
                'cremation_capacity'   => 1,
            ]);
        }
    }

    private function seedMausoleum(InternalCemetery $cemetery, string $name, int $crypts): void
    {
        Mausoleum::factory()
            ->for($cemetery, 'cemetery')
            ->state([
                'internal_cemetery_id' => $cemetery->id,
                'name'           => $name,
                'grid_reference' => 'M-01',
            ])
            ->withCrypts($crypts)
            ->create();
    }

    private function seedColumbarium(InternalCemetery $cemetery, string $name, int $niches): void
    {
        Columbarium::factory()
            ->for($cemetery, 'cemetery')
            ->state([
                'internal_cemetery_id' => $cemetery->id,
                'name'           => $name,
                'grid_reference' => 'C-01',
            ])
            ->withNiches($niches)
            ->create();
    }
}
