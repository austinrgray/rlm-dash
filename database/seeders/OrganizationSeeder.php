<?php

namespace Database\Seeders;

use App\Models\Customer\Organization;
use App\Models\Shared\ContactCard;

use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $this->randomFill();
        // $this->realisticFill();
    }

    private function randomFill(): void
    {
        Organization::factory()
            ->count(20)
            ->create()
            ->each(function (Organization $org) {
                ContactCard::factory()
                    ->withLabel($org->name)
                    ->for($org, 'contactable')
                    ->primary()
                    ->create();
            });
    }

    /**
     * Future realistic mode:
     * - Attach organizations to families (e.g. employers, insurers, churches)
     * - Pull organization names/types from a curated dataset
     * - Generate proper contact hierarchies
     */
    private function realisticFill(): void
    {
        // Example:
        // Organization::createFromRealData($data);
    }
}
