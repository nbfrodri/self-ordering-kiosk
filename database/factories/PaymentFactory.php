<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $subtotal = $this->faker->randomFloat(2, 5.00, 50.00);
        $tax = round($subtotal * 0.08, 2);
        $total = round($subtotal + $tax, 2);

        return [
            'order_number' => 'ORD-' . strtoupper($this->faker->unique()->bothify('####')),
            'payment_method' => $this->faker->randomElement(['cash', 'credit_card', 'debit_card', 'mobile_pay']),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed', 'refunded']),
            'transaction_reference' => $this->faker->optional()->uuid(),
        ];
    }

    public function completed(): static
    {
        return $this->state([
            'status' => 'completed',
            'transaction_reference' => $this->faker->uuid(),
        ]);
    }

    public function pending(): static
    {
        return $this->state([
            'status' => 'pending',
            'transaction_reference' => null,
        ]);
    }
}
