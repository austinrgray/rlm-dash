<?php

namespace Database\Factories\Cemetery;

use App\Models\Cemetery\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

class SectionFactory extends Factory
{
    protected $model = Section::class;

    public function definition(): array
    {
        return [
            'internal_cemetery_id' => null,
            'name'                 => $this->faker->word . ' Garden',
        ];
    }
}
