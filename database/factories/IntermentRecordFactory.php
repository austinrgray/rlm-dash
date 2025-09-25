<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\IntermentRecord;
use App\Models\InternalCemetery;
use App\Models\ExternalCemetery;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IntermentRecord>
 */
class IntermentRecordFactory extends Factory
{
    protected $model = IntermentRecord::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dateOfDeath = $this->faker->dateTimeBetween('-90 years', 'now');
        $useInternal = $this->faker->boolean(70);
        return [
            'date_of_death' => $dateOfDeath,
            'date_of_interment' => $this->faker->optional(0.85)->dateTimeBetween($dateOfDeath, '+30 days'),
            'internal_cemetery_id' => $useInternal ? InternalCemetery::factory() : null,
            'external_cemetery_id' => $useInternal ? null : ExternalCemetery::factory(),
            'plot_id' => null,
            'disposition_type' => $this->faker->randomElement(['Burial', 'Cremation', 'Entombment', 'Donation']),
            'vault_type' => $this->faker->optional()->word(),
            'funeral_home' => $this->faker->optional()->company(),
            'order_id' => null,
        ];
    }
}
