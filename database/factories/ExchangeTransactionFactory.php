<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExchangeTransaction>
 */
class ExchangeTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amountFrom = $this->faker->randomFloat(2, 0, 1000);
        $amountTo = $this->faker->randomFloat(2, 0, 1000);
        return [
            'user_id' => $this->faker->randomNumber(),
            'wallet_address' => $this->faker->bothify('0x????????????????????????????????????????'),
            'token_from' => $this->faker->randomElement(['tBNB', 'USDT', 'USDC']),
            'token_to' => $this->faker->randomElement(['tBNB', 'USDT', 'USDC']),
            'amount_from' => $amountFrom,
            'amount_to' => $amountTo,
            'price' => $amountTo / $amountFrom,
            'chain_id' => $this->faker->randomElement([97]),
            'transaction_hash' => $this->faker->bothify('0x????????????????????????????????????????'),
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
