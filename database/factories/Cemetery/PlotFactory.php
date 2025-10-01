<?php

namespace Database\Factories\Cemetery;

use App\Models\Cemetery\Plot;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlotFactory extends Factory
{
    protected $model = Plot::class;

    public function definition(): array
    {
        return [
            'plot_number'          => 0,
            'grid_reference'       => $this->faker->bothify('##??'),
            'traditional_capacity' => 0,
            'cremation_capacity'   => 0,
            'notes'                => $this->faker->optional(0.2)->sentence(),
        ];
    }
}
