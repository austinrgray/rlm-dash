<?php

namespace Database\Factories;

use App\Models\ContactCard;
use App\Models\IntermentRecord;
use App\Models\Person;
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
        $dateOfBirth = $this->faker->dateTimeBetween('-100 years', '-0 years');

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

    public function configure(): static
    {
        return $this->afterCreating(function (Person $person) {
            // Attach a ContactCard with 50% chance
            if ($this->faker->boolean(50)) {
                ContactCard::factory()
                    ->for($person, 'contactable')
                    ->withLabelFromPerson($person)
                    ->create();
            }

            // With 15% chance, create an IntermentRecord for this person
            if ($this->faker->boolean(15)) {
                $dateOfDeath = $this->faker->dateTimeBetween($person->date_of_birth, 'now');
                IntermentRecord::factory()->for($person)->create([
                    'date_of_death' => $dateOfDeath,
                    'date_of_interment' => $this->faker->optional(0.9)->dateTimeBetween($dateOfDeath, '+30 days'),
                    'funeral_home' => $this->faker->optional()->company(),
                ]);
            }
        });
    }
}
