<?php

namespace Database\Seeders;

use App\Models\Customer\Family;
use Illuminate\Database\Seeder;

class FamilySeeder extends Seeder
{
    public function run(): void
    {
        $this->randomFill();
        // $this->realisticFill();
    }

    /**
     * Generate families with members/contacts/organizations
     * in a semi-random way (development filler data).
     */
    private function randomFill(): void
    {
        Family::factory()->count(400)->create();
    }

    /**
     * Future: Generate families following realistic business rules.
     * For example:
     * - Link families to real burial rights / interments
     * - Seed invoices and orders tied to these families
     * - More structured demographic distribution
     */
    private function realisticFill(): void
    {
        // Example structure — not implemented yet
        // foreach (RealWorldFamilyGenerator::all() as $familyData) {
        //     Family::createFromRealistic($familyData);
        // }
    }
}
