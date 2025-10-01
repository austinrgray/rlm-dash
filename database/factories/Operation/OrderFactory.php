<?php

namespace Database\Factories\Operation;

use App\Models\Operation\Order;
use App\Models\Interment\Interment;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'family_id' => null,
            'status'    => 'open',
        ];
    }

    public function forInterment(Interment $interment): static
    {
        return $this->afterCreating(function (Order $order) use ($interment) {
            // Example: Add required line items for an interment
            $order->lineItems()->createMany([
                [
                    'description' => 'Interment Fee',
                    'amount' => fake()->numberBetween(1500, 4000),
                ],
                [
                    'description' => 'Vault',
                    'amount' => match ($interment->vault_type->value) {
                        'concrete' => 1200,
                        'steel' => 1800,
                        'bronze' => 2500,
                        default => 800,
                    },
                ],
            ]);
        });
    }
}
