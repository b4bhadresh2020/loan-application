<?php

namespace App\Http\Services;

use App\Models\LoanRepayment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class LoanRepaymentService
{
    private LoanApplicationService $loanApplicationService;

    public function __construct(LoanApplicationService $loanApplicationService)
    {
        $this->loanApplicationService = $loanApplicationService;
    }

    public function repayLoanProcess(LoanRepayment $loanRepayment): bool
    {
        $loanRepayment->update([
            'status' => LoanRepayment::STATUS['PAID'],
            'paid_amount' => $loanRepayment->emi_amount,
            'payment_date' => Carbon::now(),
        ]);

        // Loan closer (last installment)
        if ($loanRepayment->loanApplication->tenure == $loanRepayment->instalment) {
            $this->loanApplicationService->closeInstallment($loanRepayment->loanApplication);
        }

        return true;
    }

    public function findAllPending(): Collection
    {
        return LoanRepayment::where([
            ['user_id', auth()->user()->id],
            ['due_date', '<', Carbon::now()],
            ['status', LoanRepayment::STATUS['PENDING']],
        ])->get();
    }
}
