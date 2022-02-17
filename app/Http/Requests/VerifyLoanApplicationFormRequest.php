<?php

namespace App\Http\Requests;

use App\Http\Requests\StepRequest;

class VerifyLoanApplicationFormRequest extends StepRequest
{
    public function rules()
    {
        return [
            'is_approve' => 'required',
        ];
    }
}
