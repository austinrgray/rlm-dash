<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ExternalCemetery;
use App\Models\ContactCard;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExternalCemetery>
 */
class ExternalCemeteryFactory extends Factory
{
    protected $model = ExternalCemetery::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company() . ' Cemetery',
            'is_active' => true,
            'notes' => $this->faker->optional(0.3)->sentence(),
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
