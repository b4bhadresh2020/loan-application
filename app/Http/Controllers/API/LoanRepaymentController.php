<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoanApplicationResource;
use App\Http\Resources\LoanRepaymentResource;
use App\Http\Responses\ApiResponse;
use App\Http\Services\LoanApplicationService;
use App\Http\Services\LoanRepaymentService;
use App\Models\LoanRepayment;

class LoanRepaymentController extends Controller
{
    private LoanApplicationService $loanApplicationService;
    private LoanRepaymentService $loanRepaymentService;

    public function __construct(LoanApplicationService $loanApplicationService, LoanRepaymentService $loanRepaymentService)
    {
        $this->loanApplicationService = $loanApplicationService;
        $this->loanRepaymentService = $loanRepaymentService;
    }

    public function index(): ApiResponse
    {
        $userLoanRepayments = $this->loanApplicationService->findAllActive();
        $pendingRepayments = $this->loanRepaymentService->findAllPending();

        return ApiResponse::create([
            'pendingRepayment' => LoanRepaymentResource::collection($pendingRepayments),
            'activeLoan' => LoanApplicationResource::collection($userLoanRepayments)
        ]);
    }

    public function repayLoanEmi(LoanRepayment $loanRepayment): ApiResponse
    {
        if ($loanRepayment->status == LoanRepayment::STATUS['PAID']) {
            return ApiResponse::__createBadResponse('EMI already paid.');
        }

        $this->loanRepaymentService->repayLoanProcess($loanRepayment);
        return ApiResponse::__create('EMI paid successfully.');
    }
}
