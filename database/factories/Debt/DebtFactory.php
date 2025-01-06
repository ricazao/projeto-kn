<?php

namespace Database\Factories\Debt;

use App\Enums\DebtStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class DebtFactory extends Factory
{
    public function definition(): array
    {
        return [
            'debtId' => fake()->uuid(),
            'governmentId' => fake()->randomNumber(4, true),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'debtAmount' => fake()->randomFloat(0, 1000, 10000),
            'debtDueDate' => fake()->date(),
            'status' => DebtStatus::PENDING->value,
        ];
    }

    public function pending(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => DebtStatus::PENDING->value,
            ];
        });
    }

    public function processing(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => DebtStatus::PROCESSING->value,
            ];
        });
    }

    public function completed(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => DebtStatus::COMPLETED->value,
            ];
        });
    }
}
