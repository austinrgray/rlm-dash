<?php

namespace Database\Seeders;

use App\Models\Cemetery\Plot;
use App\Models\Finance\Invoice;
use App\Models\Finance\InvoiceLineItem;
use App\Models\Interment\BurialRight;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        Invoice::factory()
            ->count(300)
            ->create()
            ->each(function (Invoice $invoice) {
                $pattern = $this->pickPattern();
                $plots = $this->pickPlots($pattern);

                foreach ($plots as $plot) {
                    $burialRight = BurialRight::factory()->create([
                        'plot_id' => $plot->id,
                        'family_id' => $invoice->family_id,
                        'organization_id' => $invoice->organization_id,
                    ]);

                    InvoiceLineItem::factory()->create([
                        'invoice_id'       => $invoice->id,
                        'invoiceable_type' => BurialRight::class,
                        'invoiceable_id'   => $burialRight->id,
                        'amount'           => fake()->randomFloat(2, 500, 2000),
                        'description'      => "Burial Right: Section {$plot->lot->section->name}, Lot {$plot->lot->lot_number}{$plot->lot->lot_letter}, Plot {$plot->plot_number}",
                    ]);
                }
            });
    }

    private function pickPattern(): string
    {
        return fake()->randomElement([
            'full_lot',
            'cluster',
            'single',
            'scatter'
        ]);
    }

    private function pickPlots(string $pattern)
    {
        $available = Plot::doesntHave('burialRight')
            ->with('lot.section')
            ->orderBy('lot_id')
            ->orderBy('plot_number')
            ->get();

        if ($available->isEmpty()) return collect();

        $lots = $available->groupBy('lot_id');

        return match ($pattern) {
            'full_lot' => $lots->random(),
            'cluster'  => $lots->random()->take(min(3, $lots->first()->count())),
            'single'   => $available->random(1),
            'scatter'  => $available->random(min(3, $available->count())),
            default    => collect(),
        };
    }
}
