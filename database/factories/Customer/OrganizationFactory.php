<?php

namespace Database\Factories\Customer;

use App\Enums\Customer\OrganizationType;
use App\Models\Customer\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationFactory extends Factory
{
    protected $model = Organization::class;

    public function definition(): array
    {
        return [
            'name'      => $this->faker->company(),
            'type'      => $this->faker->randomElement(OrganizationType::cases())->value,
            'is_active' => true,
            'notes'     => $this->faker->optional(0.3)->sentence(),
            'family_id' => null,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn() => ['is_active' => false]);
    }

    public function forFamily($family): static
    {
        return $this->state(fn() => ['family_id' => $family->id ?? $family]);
    }
}
