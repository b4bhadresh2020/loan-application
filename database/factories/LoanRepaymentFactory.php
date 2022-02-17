<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LoanRepaymentFactory extends Factory
{
    public function definition()
    {
        return [
            'id' => Str::uuid(),
            'instalment' => rand(1, 10),
            'emi_amount' => $this->faker->numerify('###.##'),
            'due_date' => now()->addWeeks(1)->toDateTimeString(),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
