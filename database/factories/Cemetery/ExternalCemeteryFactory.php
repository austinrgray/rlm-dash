<?php

namespace Database\Factories\Cemetery;

use App\Models\Cemetery\ExternalCemetery;
use App\Models\Shared\ContactCard;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExternalCemeteryFactory extends Factory
{
    protected $model = ExternalCemetery::class;

    public function definition(): array
    {
        return [
            'name'      => $this->faker->company() . ' Cemetery',
            'is_active' => true,
            'notes'     => $this->faker->optional(0.3)->sentence(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (ExternalCemetery $cemetery) {
            ContactCard::factory()
                ->withLabel($cemetery->name)
                ->for($cemetery, 'contactable')
                ->create();
        });
    }
}
