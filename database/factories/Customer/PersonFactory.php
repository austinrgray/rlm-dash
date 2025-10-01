<?php

namespace Database\Factories\Customer;

use App\Enums\Customer\Gender;
use App\Models\Customer\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonFactory extends Factory
{
    protected $model = Person::class;

    public function definition(): array
    {
        $gender = $this->faker->randomElement([Gender::Male->value, Gender::Female->value]);
        $dob    = $this->faker->dateTimeBetween('-100 years', 'now');
        $dod = $this->faker->boolean(30)
            ? $this->faker->dateTimeBetween($dob, 'now')
            : null;

        return [
            'last_name'     => $this->faker->lastName(),
            'first_name'    => $this->faker->firstName($gender),
            'middle_name'   => $this->faker->optional(0.8)->firstName($gender),
            'date_of_birth' => $dob,
            'date_of_death' => $dod,
            'gender'        => $gender,
            'notes'         => $this->faker->optional(0.2)->sentence(),
        ];
    }

    public function withLastName(string $lastName): static
    {
        return $this->state(fn() => ['last_name' => $lastName]);
    }

    public function ageBetween(int $minAge, int $maxAge): static
    {
        $dob = $this->faker->dateTimeBetween("-$maxAge years", "-$minAge years");
        $deathChance = $maxAge > 70 ? 50 : ($maxAge > 40 ? 20 : 5);
        $dod = $this->faker->boolean($deathChance)
            ? $this->faker->dateTimeBetween($dob, 'now')
            : null;

        return $this->state(fn() => [
            'date_of_birth' => $dob,
            'date_of_death' => $dod,
        ]);
    }
}
