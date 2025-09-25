<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Organization;
use App\Models\ContactCard;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organization>
 */
class OrganizationFactory extends Factory
{
    protected $model = Organization::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'type' => $this->faker->randomElement([
                'business',
                'church',
                'charity',
                'school',
                'government',
                'other'
            ]),
            'is_active' => true,
            'notes' => $this->faker->optional(0.3)->sentence(),
            'family_id' => null,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn() => ['is_active' => false]);
    }

    public function forFamily(int $familyId): static
    {
        return $this->state(fn() => ['family_id' => $familyId]);
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Organization $org) {
            ContactCard::factory()
                ->for($org, 'contactable')
                ->withLabel($org->name)
                ->create();
        });
    }
}
