<?php

namespace App\Http\Services;

use App\Exceptions\BadHttpResponseException;
use App\Models\LoanTerm;

class LoanTermService
{
    public function findByWeeks(int $week): ?LoanTerm
    {
        $loanTerm = LoanTerm::where([
            ['start_weeks', '<=', $week],
            ['end_weeks', '>=', $week]
        ])->first();

        if (!$loanTerm) {
            throw new BadHttpResponseException('Invalid Loan Term');
        }

        return $loanTerm;
    }
}
