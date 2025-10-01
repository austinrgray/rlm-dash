<?php

namespace Database\Factories\Cemetery;

use App\Models\Cemetery\Lot;
use Illuminate\Database\Eloquent\Factories\Factory;

class LotFactory extends Factory
{
    protected $model = Lot::class;

    public function definition(): array
    {
        return [
            'lot_number'     => null,
            'lot_letter'     => null,
            'grid_reference' => $this->faker->bothify('##??'),
            'notes'          => $this->faker->optional(0.2)->sentence(),
        ];
    }
}
