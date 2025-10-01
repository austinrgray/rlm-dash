<?php

namespace Database\Factories\Cemetery;

use App\Models\Cemetery\Plot;
use App\Models\Finance\Invoice;
use App\Models\Interment\BurialRight;
use Illuminate\Database\Eloquent\Factories\Factory;

class BurialRightFactory extends Factory
{
    protected $model = BurialRight::class;

    public function definition(): array
    {
        $invoice = Invoice::inRandomOrder()->first();
        $plot    = Plot::doesntHave('burialRight')->inRandomOrder()->first();

        return [
            'family_id'       => $invoice?->family_id,
            'organization_id' => $invoice?->organization_id,
            'plot_id'         => $plot?->id,
            'status'          => 'active',
            'notes'           => $this->faker->optional(0.2)->sentence(),
        ];
    }

    public function forInvoice(Invoice $invoice, ?Plot $plot = null): static
    {
        return $this->state(fn() => [
            'family_id'       => $invoice->family_id,
            'organization_id' => $invoice->organization_id,
            'plot_id'         => $plot?->id ?? Plot::doesntHave('burialRight')->inRandomOrder()->first()?->id,
        ]);
    }
}
