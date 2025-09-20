<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Person>
 */
class PersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate a random date of birth between 80 and 1 year(s) ago
        $dateOfBirth = $this->faker->dateTimeBetween('-80 years', '-1 years');
        // Randomly decide if this person is deceased (20% chance)
        $isDeceased = $this->faker->boolean(20);
        // If deceased, set date of death to sometime after their birth
        $dateOfDeath = $isDeceased ? $this->faker->dateTimeBetween($dateOfBirth, 'now') : null;

        return [
            'last_name' => $this->faker->lastName(),
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->firstName(),
            'date_of_birth' => $dateOfBirth,
            'date_of_death' => $dateOfDeath,
            'notes' => $this->faker->boolean(30) ? $this->faker->sentence() : null, // 30% chance of having a note
        ];
    }
}
