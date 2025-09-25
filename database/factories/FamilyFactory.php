<?php

namespace Database\Factories;

use App\Models\Family;
use App\Models\FamilyMember;
use App\Models\Person;
use App\Models\Organization;
use App\Models\ContactCard;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Family>
 */
class FamilyFactory extends Factory
{

    protected $model = Family::class;

    private $familyTypes = [
        'single' => 5,
        'couple' => 25,
        'parent_couple' => 30,
        'parent_single' => 15,
        'extended' => 25,
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'is_active' => $this->faker->boolean(90),
            'notes' => $this->faker->optional(0.2)->sentence(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Family $family) {
            $type = $this->getWeightedRandomType();
            $familyName = $this->faker->lastName();
            $this->createFamilyByType($family, $type, $familyName);

            $head = $family->familyMembers()->where('role', 'head')->first()?->person;
            if ($head) {
                ContactCard::factory()
                    ->for($family, 'contactable')
                    ->withLabelFromPerson($head)
                    ->create();
            }

            // With 20% chance, create a ContactCard for the spouse
            $spouse = $family->familyMembers()->where('role', 'spouse')->first()?->person;
            if ($spouse && $this->faker->boolean(20)) {
                ContactCard::factory()
                    ->for($family, 'contactable')
                    ->withLabelFromPerson($spouse)
                    ->create();
            }

            if ($this->faker->boolean(25)) {
                Organization::factory()
                    ->forFamily($family->id)
                    ->create();
            }
            if ($this->faker->boolean(15)) {
                Organization::factory()
                    ->forFamily($family->id)
                    ->create();
            }
        });
    }

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

    private function createFamilyByType(Family $family, string $type, string $familyName): void
    {
        switch ($type) {
            case 'single':
                $head = Person::factory()->withLastName($familyName)->ageBetween(18, 90)->create();
                FamilyMember::factory()->for($head)->for($family)->role('head')->create();
                break;


            case 'couple':
                $head = Person::factory()->withLastName($familyName)->ageBetween(25, 55)->create();
                $spouse = Person::factory()->withLastName($familyName)->ageBetween(25, 55)->create();
                FamilyMember::factory()->for($head)->for($family)->role('head')->create();
                FamilyMember::factory()->for($spouse)->for($family)->role('spouse')->create();
                break;


            case 'parent_couple':
                $head = Person::factory()->withLastName($familyName)->ageBetween(35, 55)->create();
                $spouse = Person::factory()->withLastName($familyName)->ageBetween(35, 55)->create();
                FamilyMember::factory()->for($head)->for($family)->role('head')->create();
                FamilyMember::factory()->for($spouse)->for($family)->role('spouse')->create();


                $childCount = rand(1, 6);
                for ($i = 0; $i < $childCount; $i++) {
                    $child = Person::factory()->withLastName($familyName)->ageBetween(0, 18)->create();
                    FamilyMember::factory()->for($child)->for($family)->role('child')->create();
                }
                break;


            case 'parent_single':
                $head = Person::factory()->withLastName($familyName)->ageBetween(18, 45)->create();
                FamilyMember::factory()->for($head)->for($family)->role('head')->create();


                $childCount = rand(1, 4);
                for ($i = 0; $i < $childCount; $i++) {
                    $child = Person::factory()->withLastName($familyName)->ageBetween(0, 10)->create();
                    FamilyMember::factory()->for($child)->for($family)->role('child')->create();
                }
                break;


            case 'extended':
                $head = Person::factory()->withLastName($familyName)->ageBetween(35, 55)->create();
                $spouse = Person::factory()->withLastName($familyName)->ageBetween(35, 55)->create();
                $grandparent1 = Person::factory()->withLastName($familyName)->ageBetween(70, 90)->create();
                $grandparent2 = Person::factory()->withLastName($familyName)->ageBetween(70, 90)->create();
                FamilyMember::factory()->for($head)->for($family)->role('head')->create();
                FamilyMember::factory()->for($spouse)->for($family)->role('spouse')->create();
                FamilyMember::factory()->for($grandparent1)->for($family)->role('parent')->create();
                FamilyMember::factory()->for($grandparent2)->for($family)->role('parent')->create();


                $childCount = rand(1, 6);
                for ($i = 0; $i < $childCount; $i++) {
                    $child = Person::factory()->withLastName($familyName)->ageBetween(0, 18)->create();
                    FamilyMember::factory()->for($child)->for($family)->role('child')->create();
                }
                break;
        }
    }
}
