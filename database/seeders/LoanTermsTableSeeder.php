<?php

namespace Database\Seeders;

use App\Models\LoanTerm;

class LoanTermsTableSeeder extends BaseSeeder
{
    public function run()
    {
        LoanTerm::create(
            [
                'start_weeks' => 0,
                'end_weeks' => 4,
                'interest' => 18.0,
            ]
        );

        LoanTerm::create(
            [
                'start_weeks' => 5,
                'end_weeks' => 12,
                'interest' => 16.0,
            ]
        );

        LoanTerm::create(
            [
                'start_weeks' => 13,
                'end_weeks' => 24,
                'interest' => 14.0,
            ]
        );

        LoanTerm::create(
            [
                'start_weeks' => 25,
                'end_weeks' => 48,
                'interest' => 12.0,
            ]
        );

        LoanTerm::create(
            [
                'start_weeks' => 49,
                'end_weeks' => 9999,
                'interest' => 11.0,
            ]
        );
    }
}
