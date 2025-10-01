<?php

namespace Database\Factories\Finance;

use App\Models\Customer\Family;
use App\Models\Customer\Organization;
use App\Models\Finance\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        // Decide purchaser type (80% families, 20% orgs, tweak as needed)
        if ($this->faker->boolean(80)) {
            return [
                'family_id'       => Family::inRandomOrder()->first()?->id,
                'organization_id' => null,
                'status'          => 'open',
                'due_date'        => $this->faker->dateTimeBetween('now', '+6 months'),
            ];
        }

        return [
            'family_id'       => null,
            'organization_id' => Organization::inRandomOrder()->first()?->id,
            'status'          => 'open',
            'due_date'        => $this->faker->dateTimeBetween('now', '+6 months'),
        ];
    }

    public function forFamily(?Family $family = null): static
    {
        return $this->state(fn() => [
            'family_id'       => $family?->id ?? Family::inRandomOrder()->first()?->id,
            'organization_id' => null,
        ]);
    }

    public function forOrganization(?Organization $org = null): static
    {
        return $this->state(fn() => [
            'family_id'       => null,
            'organization_id' => $org?->id ?? Organization::inRandomOrder()->first()?->id,
        ]);
    }
}
