<?php

namespace Tests\Feature;

use App\Http\Responses\ApiResponse;
use App\Models\LoanApplication;
use Tests\BaseApiTest;

class LoanApplicationApiTest extends BaseApiTest
{
    public function test_loanApplications_expectSuccess()
    {
        LoanApplication::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->getJson(route('loan-applications.index'))
            ->assertOk();

        $this->assertCount(1, $response->json('data')['loanApplications']);
    }

    public function test_store_expectValidationErrors()
    {
        $this->postJson(route('loan-applications.store'), [])
            ->assertStatus(ApiResponse::VALIDATION)
            ->assertJsonValidationErrors([
                'amount', 'tenure_in_weeks'
            ]);
    }

    public function test_store_expectSuccess()
    {
        $this->postJson(route('loan-applications.store'), [
            'amount' => 50000,
            'tenure_in_weeks' => 12
        ])
            ->assertOk()
            ->assertJson([
                'data' => [
                    'message' => [
                        'Loan request submitted.'
                    ]
                ]
            ]);
    }

    public function test_store_expectExceptionInvalidLoanTerm()
    {
        $this->postJson(route('loan-applications.store'), [
            'amount' => 50000,
            'tenure_in_weeks' => 1000000
        ])
            ->assertStatus(ApiResponse::BAD_REQUEST)
            ->assertJson([
                'error' => [
                    'message' => [
                        'Invalid Loan Term'
                    ]
                ]
            ]);
    }
}
