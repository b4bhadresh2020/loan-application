<?php

namespace App\Http\Requests;

use App\Http\Responses\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class StepRequest extends FormRequest
{
    public function rules()
    {
        return [];
    }

    public function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(
            response()->json([
                'data'   => null,
                'errors' => $errors,
                'status' => ApiResponse::VALIDATION,
                'success' => false
            ], ApiResponse::VALIDATION)
        );
    }
}
