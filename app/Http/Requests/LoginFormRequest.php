<?php

namespace App\Http\Requests;

use App\Http\Requests\StepRequest;

class LoginFormRequest extends StepRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }
}
