<?php

namespace Database\Factories\Cemetery\Columbarium;

use App\Models\Cemetery\Columbarium\Niche;
use Illuminate\Database\Eloquent\Factories\Factory;

class NicheFactory extends Factory
{
    protected $model = Niche::class;

    public function definition(): array
    {
        return [
            'internal_cemetery_id' => null,
            'columbarium_id'       => null,
            'columbarium_name'     => $this->faker->company() . ' Columbarium',
            'niche_number'   => $this->faker->unique()->numberBetween(1, 500),
            'capacity'       => 3,
            'grid_reference' => $this->faker->bothify('N##'),
            'notes'          => $this->faker->optional(0.2)->sentence(),
        ];
    }
}
