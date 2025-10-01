<?php

namespace Database\Factories\Cemetery\Columbarium;

use App\Models\Cemetery\Columbarium\Columbarium;
use App\Models\Cemetery\Columbarium\Niche;
use App\Models\Cemetery\InternalCemetery;
use Illuminate\Database\Eloquent\Factories\Factory;

class ColumbariumFactory extends Factory
{
    protected $model = Columbarium::class;

    public function definition(): array
    {
        return [
            'internal_cemetery_id' => null,
            'name'                 => $this->faker->company() . ' Columbarium',
            'grid_reference'       => $this->faker->bothify('C-##'),
            'notes'                => $this->faker->optional(0.2)->sentence(),
        ];
    }

    public function withNiches(int $count = 20): static
    {
        return $this->afterCreating(function (Columbarium $columbarium) use ($count) {
            foreach (range(1, $count) as $number) {
                Niche::factory()->create([
                    'internal_cemetery_id' => $columbarium->internal_cemetery_id,
                    'columbarium_id'       => $columbarium->id,
                    'niche_number'         => $number,
                    'capacity'             => 3,
                ]);
            }
        });
    }
}
