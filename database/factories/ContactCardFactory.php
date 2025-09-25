<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactCard>
 */
class ContactCardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label' => $this->faker->name(),
            'phone' => $this->faker->optional(0.8)->phoneNumber(),
            'email' => $this->faker->optional(0.7)->safeEmail(),
            'address_line_1' => $this->faker->optional(0.6)->streetAddress(),
            'address_line_2' => $this->faker->optional(0.2)->secondaryAddress(),
            'city' => $this->faker->optional(0.6)->city(),
            'state' => $this->faker->optional(0.6)->stateAbbr(),
            'zip' => $this->faker->optional(0.6)->postcode(),
            'is_active' => $this->faker->boolean(90),
        ];
    }

    public function withLabelFromPerson($person): static
    {
        return $this->state(fn() => [
            'label' => trim($person->first_name . ' ' . ($person->middle_name ? $person->middle_name . ' ' : '') . $person->last_name),
        ]);
    }
}
