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
        $gender = $this->faker->randomElement(['male', 'female']);
        $dateOfBirth = $this->faker->dateTimeBetween('-90 years', '-18 years');
        $isDeceased = $this->faker->boolean(15);
        $dateOfDeath = $isDeceased ? $this->faker->dateTimeBetween($dateOfBirth, 'now') : null;

        return [
            'last_name' => $this->faker->lastName(),
            'first_name' => $this->faker->firstName($gender),
            'middle_name' => $this->faker->optional(0.8)->firstName($gender),
            'date_of_birth' => $dateOfBirth,
            'gender' => $gender,
            'notes' => $this->faker->optional(0.2)->sentence(),
        ];
    }

    public function withLastName(string $lastName): static
    {
        return $this->state(fn() => ['last_name' => $lastName]);
    }

    public function ageBetween(int $minAge, int $maxAge): static
    {
        return $this->state(fn() => [
            'date_of_birth' => $this->faker->dateTimeBetween("-$maxAge years", "-$minAge years")
        ]);
    }

    public function deceased(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'date_of_death' => $this->faker->dateTimeBetween($attributes['date_of_birth'], 'now'),
            ];
        });
    }
}
