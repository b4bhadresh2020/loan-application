<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LoanApplicationFactory extends Factory
{
    public function definition()
    {
        return [
            'amount' => $this->faker->numerify('###.##'),
            'tenure' => rand(5, 20),
            'interest' => $this->faker->numerify('##.##'),
        ];
    }
}
