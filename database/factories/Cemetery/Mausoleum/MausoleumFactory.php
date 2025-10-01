<?php

namespace Database\Factories\Cemetery\Mausoleum;

use App\Models\Cemetery\Mausoleum\Crypt;
use App\Models\Cemetery\Mausoleum\Mausoleum;
use App\Models\Cemetery\InternalCemetery;
use Illuminate\Database\Eloquent\Factories\Factory;

class MausoleumFactory extends Factory
{
    protected $model = Mausoleum::class;

    public function definition(): array
    {
        return [
            'internal_cemetery_id' => null,
            'name'                 => $this->faker->company() . ' Mausoleum',
            'grid_reference'       => $this->faker->bothify('M-##'),
            'notes'                => $this->faker->optional(0.2)->sentence(),
        ];
    }

    public function withCrypts(int $count = 10): static
    {
        return $this->afterCreating(function (Mausoleum $mausoleum) use ($count) {
            foreach (range(1, $count) as $number) {
                Crypt::factory()->create([
                    'internal_cemetery_id' => $mausoleum->internal_cemetery_id,
                    'mausoleum_id'         => $mausoleum->id,
                    'crypt_number'         => $number,
                    'capacity'             => 2,
                ]);
            }
        });
    }
}
