<?php

namespace Database\Factories\Customer;

use App\Enums\Customer\FamilyRole;
use App\Models\Customer\FamilyMember;
use Illuminate\Database\Eloquent\Factories\Factory;

class FamilyMemberFactory extends Factory
{
    protected $model = FamilyMember::class;

    public function definition(): array
    {
        return [
            'role' => FamilyRole::Other->value,
        ];
    }

    public function role(string $role): static
    {
        return $this->state(fn() => ['role' => $role]);
    }
}
