<?php

namespace Database\Factories\Shared;

use App\Models\Shared\ContactCard;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactCardFactory extends Factory
{
    protected $model = ContactCard::class;

    public function definition(): array
    {
        return [
            'contactable_type' => null,
            'contactable_id'   => null,
            'label'            => $this->faker->name(),
            'phone'            => $this->faker->numerify('(###) ###-####'),
            'email'            => $this->faker->optional(0.7)->safeEmail(),
            'address_line_1'   => $this->faker->optional(0.6)->streetAddress(),
            'address_line_2'   => $this->faker->optional(0.2)->secondaryAddress(),
            'city'             => $this->faker->optional(0.6)->city(),
            'state'            => $this->faker->optional(0.6)->stateAbbr(),
            'zip'              => $this->faker->optional(0.6)->postcode(),
            'is_active'        => $this->faker->boolean(99),
            'is_primary'       => false,
        ];
    }

    public function primary(): static
    {
        return $this->state(fn() => ['is_primary' => true]);
    }

    public function secondary(): static
    {
        return $this->state(fn() => ['is_primary' => false]);
    }

    public function withLabelFromPerson($person): static
    {
        return $this->state(fn() => [
            'label' => trim($person->first_name . ' ' . ($person->middle_name ? $person->middle_name . ' ' : '') . $person->last_name),
        ]);
    }

    public function withLabel(string $label): static
    {
        return $this->state(fn() => ['label' => $label]);
    }
}
