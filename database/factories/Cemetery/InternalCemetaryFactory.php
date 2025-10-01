<?php

namespace Database\Factories\Cemetery;

use App\Models\Cemetery\InternalCemetery;
use Illuminate\Database\Eloquent\Factories\Factory;

class InternalCemeteryFactory extends Factory
{
    protected $model = InternalCemetery::class;

    public function definition(): array
    {
        return [
            'name'  => $this->faker->company() . ' Memorial Gardens',
            'notes' => $this->faker->optional(0.2)->sentence(),
        ];
    }
}
