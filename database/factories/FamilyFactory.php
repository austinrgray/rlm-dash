<?php

namespace Database\Factories;

use App\Models\Family;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Family>
 */
class FamilyFactory extends Factory
{

    protected $model = Family::class;

    private $familyTypes = [
        'single' => 5,
        'couple' => 15,
        'parent_couple' => 30,
        'parent_single' => 10,
        'extended' => 40,
    ];
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'notes' => $this->faker->optional(0.2)->sentence(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Family $family) {
            $type = $this->getWeightedRandomType();
            $familyName = $this->faker->lastName();
            $this->createFamilyByType($family, $type, $familyName);
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
                $head = PersonFactory::new()->withLastName($familyName)->ageBetween(18, 90)->create();
                FamilyMemberFactory::new()->for($head)->for($family)->role('head')->create();
                break;

            case 'couple':
                $head = PersonFactory::new()->withLastName($familyName)->ageBetween(25, 55)->create();
                $spouse = PersonFactory::new()->withLastName($familyName)->ageBetween(25, 55)->create();
                FamilyMemberFactory::new()->for($head)->for($family)->role('head')->create();
                FamilyMemberFactory::new()->for($spouse)->for($family)->role('spouse')->create();
                break;

            case 'parent_couple':
                $head = PersonFactory::new()->withLastName($familyName)->ageBetween(35, 55)->create();
                $spouse = PersonFactory::new()->withLastName($familyName)->ageBetween(35, 55)->create();
                FamilyMemberFactory::new()->for($head)->for($family)->role('head')->create();
                FamilyMemberFactory::new()->for($spouse)->for($family)->role('spouse')->create();

                $childCount = rand(1, 6);
                for ($i = 0; $i < $childCount; $i++) {
                    $child = PersonFactory::new()->withLastName($familyName)->ageBetween(0, 18)->create();
                    FamilyMemberFactory::new()->for($child)->for($family)->role('child')->create();
                }
                break;

            case 'parent_single':
                $head = PersonFactory::new()->withLastName($familyName)->ageBetween(18, 45)->create();
                FamilyMemberFactory::new()->for($head)->for($family)->role('head')->create();

                $childCount = rand(1, 4);
                for ($i = 0; $i < $childCount; $i++) {
                    $child = PersonFactory::new()->withLastName($familyName)->ageBetween(0, 10)->create();
                    FamilyMemberFactory::new()->for($child)->for($family)->role('child')->create();
                }
                break;

            case 'extended':
                $head = PersonFactory::new()->withLastName($familyName)->ageBetween(35, 55)->create();
                $spouse = PersonFactory::new()->withLastName($familyName)->ageBetween(35, 55)->create();
                $grandparent1 = PersonFactory::new()->withLastName($familyName)->ageBetween(70, 90)->create();
                $grandparent2 = PersonFactory::new()->withLastName($familyName)->ageBetween(70, 90)->create();
                FamilyMemberFactory::new()->for($head)->for($family)->role('head')->create();
                FamilyMemberFactory::new()->for($spouse)->for($family)->role('spouse')->create();
                FamilyMemberFactory::new()->for($grandparent1)->for($family)->role('parent')->create();
                FamilyMemberFactory::new()->for($grandparent2)->for($family)->role('parent')->create();

                $childCount = rand(1, 6);
                for ($i = 0; $i < $childCount; $i++) {
                    $child = PersonFactory::new()->withLastName($familyName)->ageBetween(0, 18)->create();
                    FamilyMemberFactory::new()->for($child)->for($family)->role('child')->create();
                }
                break;
        }
    }
}
