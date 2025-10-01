<?php

namespace Database\Factories\Interment;

use App\Enums\Interment\DispositionType;
use App\Enums\Interment\VaultType;
use App\Enums\Interment\IntermentStatus;
use App\Models\Cemetery\ExternalCemetery;
use App\Models\Interment\Interment;
use App\Models\Customer\Person;
use App\Models\Customer\Plot;
use Illuminate\Database\Eloquent\Factories\Factory;

class IntermentRecordFactory extends Factory
{
    protected $model = Interment::class;

    public function definition(): array
    {
        $deathDate = $this->faker->dateTimeBetween('-40 years', 'now -1 year');
        $intermentDate = $this->faker->dateTimeBetween($deathDate, 'now');

        return [
            'person_id'           => null,
            'external_cemetery_id' => null,
            'plot_id'             => null,
            'date_of_death'       => $deathDate,
            'date_of_interment'   => $intermentDate,
            'disposition_type'    => $this->faker->randomElement(DispositionType::cases()),
            'vault_type'          => $this->faker->randomElement(VaultType::cases()),
            'funeral_home'        => $this->faker->company,
            'status'              => $this->faker->randomElement(IntermentStatus::cases()),
        ];
    }
}
