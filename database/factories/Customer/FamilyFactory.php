<?php

namespace Database\Factories\Customer;

use App\Models\Customer\Family;
use App\Models\Customer\FamilyMember;
use App\Enums\Customer\FamilyRole;
use App\Models\Customer\Organization;
use App\Models\Customer\Person;
use App\Models\Shared\ContactCard;
use Illuminate\Database\Eloquent\Factories\Factory;

class FamilyFactory extends Factory
{
    protected $model = Family::class;

    // Weighted family type distribution
    private array $familyTypes = [
        'single'        => 5,
        'couple'        => 25,
        'parent_couple' => 30,
        'parent_single' => 15,
        'extended'      => 25,
    ];

    public function definition(): array
    {
        return [
            'is_active' => $this->faker->boolean(90),
            'notes'     => $this->faker->optional(0.2)->sentence(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Family $family) {
            $type = $this->getWeightedRandomType();
            $familyName = $this->faker->lastName();

            // Create members depending on family type
            $this->createFamilyByType($family, $type, $familyName);

            // Create contact cards + orgs
            $this->createContacts($family);
            $this->createOrganizations($family);
        });
    }

    /**
     * Pick a random family type using weighted probabilities.
     */
    private function getWeightedRandomType(): string
    {
        $total = array_sum($this->familyTypes);
        $rand = rand(1, $total);
        $current = 0;

        foreach ($this->familyTypes as $type => $weight) {
            $current += $weight;
            if ($rand <= $current) {
                return $type;
            }
        }

        return array_key_first($this->familyTypes);
    }

    /**
     * Create family members according to type (single, couple, extended, etc.).
     */
    private function createFamilyByType(Family $family, string $type, string $familyName): void
    {
        switch ($type) {
            case 'single':
                $head = Person::factory()->withLastName($familyName)->ageBetween(18, 90)->create();
                FamilyMember::factory()->for($head)->for($family)->role(FamilyRole::HeadOfFamily->value)->create();
                break;

            case 'couple':
                $head   = Person::factory()->withLastName($familyName)->ageBetween(25, 55)->create();
                $spouse = Person::factory()->withLastName($familyName)->ageBetween(25, 55)->create();
                FamilyMember::factory()->for($head)->for($family)->role(FamilyRole::HeadOfFamily->value)->create();
                FamilyMember::factory()->for($spouse)->for($family)->role(FamilyRole::Spouse->value)->create();
                break;

            case 'parent_couple':
                $head   = Person::factory()->withLastName($familyName)->ageBetween(35, 55)->create();
                $spouse = Person::factory()->withLastName($familyName)->ageBetween(35, 55)->create();
                FamilyMember::factory()->for($head)->for($family)->role(FamilyRole::HeadOfFamily->value)->create();
                FamilyMember::factory()->for($spouse)->for($family)->role(FamilyRole::Spouse->value)->create();

                foreach (range(1, rand(1, 6)) as $i) {
                    $child = Person::factory()->withLastName($familyName)->ageBetween(0, 18)->create();
                    FamilyMember::factory()->for($child)->for($family)->role(FamilyRole::Child->value)->create();
                }
                break;

            case 'parent_single':
                $head = Person::factory()->withLastName($familyName)->ageBetween(18, 45)->create();
                FamilyMember::factory()->for($head)->for($family)->role(FamilyRole::HeadOfFamily->value)->create();

                foreach (range(1, rand(1, 4)) as $i) {
                    $child = Person::factory()->withLastName($familyName)->ageBetween(0, 10)->create();
                    FamilyMember::factory()->for($child)->for($family)->role(FamilyRole::Child->value)->create();
                }
                break;

            case 'extended':
                $head        = Person::factory()->withLastName($familyName)->ageBetween(35, 55)->create();
                $spouse      = Person::factory()->withLastName($familyName)->ageBetween(35, 55)->create();
                $grandparent1 = Person::factory()->withLastName($familyName)->ageBetween(70, 90)->create();
                $grandparent2 = Person::factory()->withLastName($familyName)->ageBetween(70, 90)->create();

                FamilyMember::factory()->for($head)->for($family)->role(FamilyRole::HeadOfFamily->value)->create();
                FamilyMember::factory()->for($spouse)->for($family)->role(FamilyRole::Spouse->value)->create();
                FamilyMember::factory()->for($grandparent1)->for($family)->role(FamilyRole::Parent->value)->create();
                FamilyMember::factory()->for($grandparent2)->for($family)->role(FamilyRole::Parent->value)->create();

                foreach (range(1, rand(1, 6)) as $i) {
                    $child = Person::factory()->withLastName($familyName)->ageBetween(0, 18)->create();
                    FamilyMember::factory()->for($child)->for($family)->role(FamilyRole::Child->value)->create();
                }
                break;
        }
    }

    public function withChildren(int $min = 1, int $max = 3): static
    {
        return $this->afterCreating(function (Family $family) use ($min, $max) {
            $familyName = $family->familyMembers()
                ->where('role', FamilyRole::HeadOfFamily->value)
                ->first()?->person?->last_name ?? $this->faker->lastName();

            foreach (range(1, rand($min, $max)) as $i) {
                $child = Person::factory()->withLastName($familyName)->ageBetween(0, 18)->create();
                FamilyMember::factory()->for($child)->for($family)->role(FamilyRole::Child->value)->create();
            }
        });
    }

    /**
     * Generate contact cards for head of household, spouse, etc.
     */
    private function createContacts(Family $family): void
    {
        $head = $family->familyMembers()
            ->where('role', FamilyRole::HeadOfFamily->value)
            ->first()?->person;

        if ($head) {
            ContactCard::factory()
                ->for($family, 'contactable')
                ->withLabelFromPerson($head)
                ->primary()
                ->create();
        }

        $spouse = $family->familyMembers()
            ->where('role', FamilyRole::Spouse->value)
            ->first()?->person;

        if ($spouse && $this->faker->boolean(20)) {
            ContactCard::factory()
                ->for($family, 'contactable')
                ->withLabelFromPerson($spouse)
                ->secondary()
                ->create();
        }
    }

    /**
     * Optionally generate organizations linked to family.
     */
    private function createOrganizations(Family $family): void
    {
        if ($this->faker->boolean(30)) {
            Organization::factory()
                ->count($this->faker->numberBetween(1, 3))
                ->forFamily($family)
                ->create()
                ->each(function (Organization $org) {
                    ContactCard::factory()
                        ->withLabel($org->name)
                        ->for($org, 'contactable')
                        ->primary()
                        ->create();
                });
        }
    }
}
