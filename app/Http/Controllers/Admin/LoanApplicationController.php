<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyLoanApplicationFormRequest;
use App\Http\Resources\LoanApplicationResource;
use App\Http\Responses\ApiResponse;
use App\Http\Services\LoanApplicationService;
use App\Models\LoanApplication;

class LoanApplicationController extends Controller
{
    private LoanApplicationService $loanApplicationService;

    public function __construct(LoanApplicationService $loanApplicationService)
    {
        $this->loanApplicationService = $loanApplicationService;
    }

    public function show(LoanApplication $loanApplication): ApiResponse
    {
        return ApiResponse::create(new LoanApplicationResource($loanApplication));
    }

    public function verifyLoanApplication(LoanApplication $loanApplication, VerifyLoanApplicationFormRequest $request): ApiResponse
    {
        $data = $request->validated();

        if (LoanApplication::STATUS['APPLIED'] != $loanApplication->status) {
            return ApiResponse::__createBadResponse('Loan was already processed');
        }

        $isApprove = filter_var($data['is_approve'], FILTER_VALIDATE_BOOLEAN);

        $this->loanApplicationService->verifyLoanApplication($loanApplication, $isApprove);
        return ApiResponse::__create('Loan process completed');
    }
}
