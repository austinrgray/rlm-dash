<?php

namespace Database\Factories\Finance;

use App\Models\Finance\InvoiceLineItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceLineItemFactory extends Factory
{
    protected $model = InvoiceLineItem::class;

    public function definition(): array
    {
        return [
            'description' => $this->faker->sentence(4),
            'amount'      => $this->faker->randomFloat(2, 500, 2000),
        ];
    }
}
