<?php

namespace App\Http\Requests;

use App\Http\Requests\StepRequest;

class RegisterFormRequest extends StepRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email|unique:App\Models\User,email',
            'password' => 'required|confirmed',
            'name' => 'required|string',
        ];
    }
}
