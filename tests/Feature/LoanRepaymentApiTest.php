<?php

namespace Tests\Feature;

use App\Http\Responses\ApiResponse;
use App\Models\LoanApplication;
use App\Models\LoanRepayment;
use Tests\BaseApiTest;

class LoanRepaymentApiTest extends BaseApiTest
{
    public function test_loanRepayments_expectSuccess()
    {
        $loanApplication = LoanApplication::factory()->create([
            'user_id' => $this->user->id,
            'status' => LoanApplication::STATUS['APPROVED']
        ]);

        LoanRepayment::factory(10)->create([
            'loan_application_id' => $loanApplication->id,
            'user_id' => $this->user->id,
            'status' => LoanRepayment::STATUS['PENDING'],
            'due_date' => now()->subDay()
        ]);

        $response = $this->actingAs($this->user, 'api')->getJson(route('repayments'))
            ->assertOk();

        $this->assertCount(1, $response->json('data')['activeLoan']);
        $this->assertCount(10, $response->json('data')['pendingRepayment']);
    }

    public function test_repayLoanEmi_expectException_emiAlreadyPay()
    {
        $loanApplication = LoanApplication::factory()->create([
            'user_id' => $this->user->id,
            'status' => LoanApplication::STATUS['APPROVED']
        ]);

        $loanRepayment = LoanRepayment::factory()->create([
            'loan_application_id' => $loanApplication->id,
            'user_id' => $this->user->id,
            'status' => LoanRepayment::STATUS['PAID'],
            'due_date' => now()->subDay()
        ]);

        $this->actingAs($this->user, 'api')
            ->postJson(route('repay-loan-emi', ['loanRepayment' => $loanRepayment]))
            ->assertStatus(ApiResponse::BAD_REQUEST)
            ->assertJson([
                'error' => [
                    'message' => [
                        'EMI already paid.'
                    ]
                ]
            ]);
    }

    public function test_repayLoanEmi_expectSuccess()
    {
        $loanApplication = LoanApplication::factory()->create([
            'user_id' => $this->user->id,
            'status' => LoanApplication::STATUS['APPROVED']
        ]);

        $loanRepayment = LoanRepayment::factory()->create([
            'loan_application_id' => $loanApplication->id,
            'user_id' => $this->user->id,
            'status' => LoanRepayment::STATUS['PENDING'],
            'due_date' => now()->subDay()
        ]);

        $this->actingAs($this->user, 'api')
            ->postJson(route('repay-loan-emi', ['loanRepayment' => $loanRepayment]))
            ->assertOk()
            ->assertJson([
                'data' => [
                    'message' => [
                        'EMI paid successfully.'
                    ]
                ]
            ]);
    }

    public function test_repayLoanEmi_closeLoanApplication_expectSuccess()
    {
        $loanApplication = LoanApplication::factory()->create([
            'user_id' => $this->user->id,
            'tenure' => 2
        ]);

        $loanRepayment = LoanRepayment::factory()->create([
            'loan_application_id' => $loanApplication->id,
            'user_id' => $this->user->id,
            'status' => LoanRepayment::STATUS['PENDING'],
            'due_date' => now()->subDay(),
            'instalment' => 2
        ]);

        $this->actingAs($this->user, 'api')
            ->postJson(route('repay-loan-emi', ['loanRepayment' => $loanRepayment]))
            ->assertOk()
            ->assertJson([
                'data' => [
                    'message' => [
                        'EMI paid successfully.'
                    ]
                ]
            ]);

        $this->assertDatabaseHas('loan_applications', ['status' => LoanApplication::STATUS['CLOSED']]);
    }
}
