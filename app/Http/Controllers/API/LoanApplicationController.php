<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateLoanApplicationFormRequest;
use App\Http\Resources\LoanApplicationResource;
use App\Http\Responses\ApiResponse;
use App\Http\Services\LoanApplicationService;
use App\Http\Services\LoanTermService;
use App\Models\LoanApplication;

class LoanApplicationController extends Controller
{
    private LoanTermService $loanTermService;
    private LoanApplicationService $loanApplicationService;

    public function __construct(LoanTermService $loanTermService, LoanApplicationService $loanApplicationService)
    {
        $this->loanTermService = $loanTermService;
        $this->loanApplicationService = $loanApplicationService;
    }

    public function index(): ApiResponse
    {
        $loanApplications = $this->loanApplicationService->findAll();

        return ApiResponse::create([
            'loanApplications' => LoanApplicationResource::collection($loanApplications)
        ]);
    }

    public function store(CreateLoanApplicationFormRequest $request): ApiResponse
    {
        $data = $request->validated();
        $tenure = $this->loanTermService->findByWeeks($data['tenure_in_weeks']);

        LoanApplication::create([
            'user_id' => auth()->user()->id,
            'amount' => $data['amount'],
            'tenure' => $data['tenure_in_weeks'],
            'interest' => $tenure->interest
        ]);

        return ApiResponse::__create('Loan request submitted.');
    }
}
