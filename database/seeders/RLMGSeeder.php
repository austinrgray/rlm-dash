<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InternalCemetery;
use App\Models\Section;
use App\Models\Lot;
use App\Models\Plot;

class RLMGSeeder extends Seeder
{
    public function run(): void
    {
        $cemetery = InternalCemetery::firstOrCreate([
            'name' => 'Rose Lawn Memorial Gardens',
        ]);

        $this->createSectionWithLotsAndPlots($cemetery, 'Savior Garden', 1, 400, 6);
        $this->createSectionWithLotsAndPlots($cemetery, 'Prayer Garden', 401, 600, 6);
        $this->createSectionWithLotsAndPlots($cemetery, 'Stone Garden', 601, 800, 6);
        $this->createSectionWithLotsAndPlots($cemetery, 'Devotion Garden', 801, 1200, 6);
        $this->createSectionWithLotsAndPlots($cemetery, 'Serenity Garden', 1201, 1500, 6);
        $this->createMausoleum($cemetery, 'Mausoleum', 42);
        $this->createColumbarium($cemetery, 'Columbarium', 150);
    }

    private function createSectionWithLotsAndPlots($cemetery, string $sectionName, int $lotStart, int $lotEnd, int $plotsPerLot)
    {
        $section = Section::create([
            'internal_cemetery_id' => $cemetery->id,
            'name' => $sectionName,
        ]);

        for ($lotNumber = $lotStart; $lotNumber <= $lotEnd; $lotNumber++) {
            $lot = Lot::create([
                'section_id' => $section->id,
                'lot_number' => $lotNumber,
                'max_capacity' => $plotsPerLot,
                'available_plot_count' => $plotsPerLot,
            ]);

            for ($plotNumber = 1; $plotNumber <= $plotsPerLot; $plotNumber++) {
                Plot::create([
                    'lot_id' => $lot->id,
                    'plot_number' => $plotNumber,
                    'traditional_burials' => 0,
                    'cremation_burials' => 0,
                    'max_traditional_burials' => 1,
                    'max_cremation_burials' => 1,
                ]);
            }
        }
    }

    private function createMausoleum($cemetery, string $sectionName, int $vaults)
    {
        $section = Section::create([
            'internal_cemetery_id' => $cemetery->id,
            'name' => $sectionName,
        ]);

        $lot = Lot::create([
            'section_id' => $section->id,
            'lot_letter' => 'A',
            'max_capacity' => $vaults,
            'available_plot_count' => $vaults,
        ]);

        for ($vaultNumber = 1; $vaultNumber <= $vaults; $vaultNumber++) {
            Plot::create([
                'lot_id' => $lot->id,
                'plot_number' => $vaultNumber,
                'traditional_burials' => 0,
                'cremation_burials' => 0,
                'max_traditional_burials' => 1,
                'max_cremation_burials' => 0,
            ]);
        }
    }

    private function createColumbarium($cemetery, string $sectionName, int $slots)
    {
        $section = Section::create([
            'internal_cemetery_id' => $cemetery->id,
            'name' => $sectionName,
        ]);

        $lot = Lot::create([
            'section_id' => $section->id,
            'lot_letter' => 'A',
            'max_capacity' => $slots,
            'available_plot_count' => $slots,
        ]);

        for ($slotNumber = 1; $slotNumber <= $slots; $slotNumber++) {
            Plot::create([
                'lot_id' => $lot->id,
                'plot_number' => $slotNumber,
                'traditional_burials' => 0,
                'cremation_burials' => 0,
                'max_traditional_burials' => 0,
                'max_cremation_burials' => 2,
            ]);
        }
    }
}
