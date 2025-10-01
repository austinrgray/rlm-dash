<?php

namespace Database\Seeders;

use App\Enums\Interment\DispositionType;
use App\Enums\Interment\VaultType;
use App\Models\Cemetery\ExternalCemetery;
use App\Models\Cemetery\Plot;
use App\Models\Customer\Family;
use App\Models\Interment\BurialRight;
use App\Models\Interment\Interment;
use App\Models\Finance\Invoice;
use App\Models\Finance\Payment;
use App\Models\Operation\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IntermentRecordSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            Family::with('members')->get()->each(function ($family) {
                $deceasedMembers = $family->members->filter(fn($member) => $member->date_of_death);

                foreach ($deceasedMembers as $person) {
                    if (fake()->boolean(20)) {
                        $this->createExternalInterment($person, $family);
                    } else {
                        $this->createInternalInterment($person, $family);
                    }
                }
            });
        });
    }

    private function createExternalInterment($person, $family): void
    {
        $externalCemetery = ExternalCemetery::inRandomOrder()->first();

        Interment::factory()->create([
            'person_id'            => $person->id,
            'external_cemetery_id' => $externalCemetery->id,
            'plot_id'              => null,
            'date_of_death'        => $person->date_of_death,
            'date_of_interment'    => fake()->dateTimeBetween($person->date_of_death, 'now'),
            'disposition_type'     => DispositionType::TraditionalBurial,
            'vault_type'           => VaultType::Unknown,
        ]);
    }

    private function createInternalInterment($person, $family): void
    {
        $deathDate = fake()->dateTimeBetween($person->date_of_birth ?? '-90 years', 'now -1 year');
        $intermentDate = fake()->dateTimeBetween($deathDate, 'now');

        $burialRight = BurialRight::firstOrCreate(
            ['family_id' => $family->id],
            ['plot_id' => Plot::inRandomOrder()->first()->id],
        );

        $plot = $burialRight->plot ?? Plot::inRandomOrder()->first();

        $disposition = $this->pickDispositionForPlot($plot);
        $vaultType = $this->pickVaultForDisposition($disposition);

        $interment = Interment::factory()->create([
            'person_id'         => $person->id,
            'plot_id'           => $plot->id,
            'date_of_death'     => $deathDate,
            'date_of_interment' => $intermentDate,
            'disposition_type'  => $disposition,
            'vault_type'        => $vaultType,
        ]);

        $order = Order::factory()->create([
            'family_id' => $family->id,
        ]);

        $invoice = Invoice::factory()->create([
            'order_id'  => $order->id,
            'family_id' => $family->id,
            'amount'    => fake()->numberBetween(2000, 8000),
        ]);

        Payment::factory()->create([
            'invoice_id' => $invoice->id,
            'amount'     => $invoice->amount,
        ]);

        $plot->applyInterment($disposition);
    }


    private function pickDispositionForPlot(Plot $plot): DispositionType
    {
        if ($plot->max_traditional_burials > 0) {
            return DispositionType::TraditionalBurial;
        }
        if ($plot->max_cremation_burials > 0) {
            return DispositionType::CremationBurial;
        }
        if ($plot->max_mausoleum_entombments > 0) {
            return DispositionType::CryptEntombment;
        }
        if ($plot->max_columbarium_entombments > 0) {
            return DispositionType::NicheInurnment;
        }

        return DispositionType::TraditionalBurial;
    }

    private function pickVaultForDisposition(DispositionType $type) //: VaultType
    {
        // $valid = $type->isValidVault();
        // return fake()->randomElement($valid);
    }
}
