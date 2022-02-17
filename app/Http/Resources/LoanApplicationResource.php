<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanApplicationResource extends JsonResource
{
    public function toArray($request)
    {
        return collect(parent::toArray($request))->merge([
            'user' => new UserResource($this->user),
            'loan_repayments' => LoanRepaymentResource::collection($this->loanRepayments),
            'approver' => new UserResource($this->approver),
        ]);
    }
}
