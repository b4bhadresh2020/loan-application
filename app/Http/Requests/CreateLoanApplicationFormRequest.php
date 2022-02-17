<?php

namespace App\Http\Requests;

use App\Http\Requests\StepRequest;

class CreateLoanApplicationFormRequest extends StepRequest
{
    public function rules()
    {
        return [
            'amount' => 'required',
            'tenure_in_weeks' => 'required|max:999|min:0',
        ];
    }
}
