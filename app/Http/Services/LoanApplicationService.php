<?php

namespace App\Http\Services;

use App\Models\LoanApplication;
use App\Models\LoanRepayment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LoanApplicationService
{
    public function findAll(): Collection
    {
        return LoanApplication::where('user_id', auth()->user()->id)
            ->get();
    }

    public function findAllActive(): Collection
    {
        return LoanApplication::where('user_id', auth()->user()->id)
            ->active()
            ->get();
    }

    public function verifyLoanApplication(LoanApplication $loanApplication, bool $isApprove): bool
    {
        $loanApplication->update([
            'status' => $isApprove ? LoanApplication::STATUS['APPROVED'] : LoanApplication::STATUS['REJECTED'],
            'approver_id' => auth()->user()->id
        ]);

        if ($isApprove) {
            $loanRepayments = $this->getWeeklyLoanRepayments($loanApplication);
            LoanRepayment::insert($loanRepayments);
        }

        DB::commit();

        return true;
    }

    public function getWeeklyLoanRepayments(LoanApplication $loanApplication): array
    {
        $paymentAmount = $loanApplication->amount + ($loanApplication->amount * $loanApplication->interest * ($loanApplication->tenure / config('app.aliases.total_weeks')) * config('app.aliases.simple_interest_rate')); // we are approch simple on loan amount. here is the formula interest = P + (P * T * R) / 100;

        $paymentDate = Carbon::today(); // current week Monday
        $loanRepayments = [];

        for ($i = 1; $i <= $loanApplication->tenure; $i++) {
            $loanRepayments[] = [
                'id' => Str::uuid(),
                'user_id' => $loanApplication->user_id,
                'loan_application_id' => $loanApplication->id,
                'instalment' => $i,
                'emi_amount' => $paymentAmount / $loanApplication->tenure,
                'due_date' => $paymentDate->addWeeks(1)->toDateTimeString()
            ];
        }

        return $loanRepayments;
    }

    public function closeInstallment(LoanApplication $loanApplication)
    {
        return $loanApplication->update(['status' => LoanApplication::STATUS['CLOSED']]);
    }
}
