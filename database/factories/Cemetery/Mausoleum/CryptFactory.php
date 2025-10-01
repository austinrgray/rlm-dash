<?php

namespace Database\Factories\Cemetery\Mausoleum;

use App\Models\Cemetery\Mausoleum\Crypt;
use App\Models\Cemetery\Mausoleum\Mausoleum;
use Illuminate\Database\Eloquent\Factories\Factory;

class CryptFactory extends Factory
{
    protected $model = Crypt::class;

    public function definition(): array
    {
        return [
            'internal_cemetery_id' => null, // or null if seeder overrides
            'mausoleum_id'         => null,
            'mausoleum_name'       => $this->faker->company() . ' Mausoleum',
            'crypt_number'   => $this->faker->unique()->numberBetween(1, 500),
            'capacity'       => 2,
            'grid_reference' => $this->faker->bothify('C##'),
            'notes'          => $this->faker->optional(0.2)->sentence(),
        ];
    }
}
