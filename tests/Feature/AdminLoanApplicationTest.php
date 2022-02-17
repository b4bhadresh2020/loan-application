<?php

namespace Tests\Feature;

use App\Http\Responses\ApiResponse;
use App\Models\LoanApplication;
use App\Models\LoanRepayment;
use App\Models\User;
use Tests\BaseApiTest;

class AdminLoanApplicationTest extends BaseApiTest
{
    public function test_showLoadApplication_expectSuccess()
    {
        $loanApplication = LoanApplication::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $admin = User::factory([
            'role' => User::ROLE['ADMIN']
        ])->create();

        $this->actingAs($admin, 'api')
            ->getJson(route('admin.loan-application.show', ['loanApplication' => $loanApplication]))
            ->assertOk()
            ->assertJson([
                'data' => $loanApplication->toArray()
            ]);
    }

    public function test_showLoadApplication_expectExceptionNotFound()
    {
        $loanApplication = LoanApplication::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $user = User::factory([
            'role' => User::ROLE['USER']
        ])->create();

        $this->actingAs($user, 'api')
            ->getJson(route('admin.loan-application.show', ['loanApplication' => $loanApplication]))
            ->assertStatus(ApiResponse::NOT_FOUND);
    }

    public function test_showLoadApplication_expectExceptionModelNotFound()
    {
        $admin = User::factory([
            'role' => User::ROLE['ADMIN']
        ])->create();

        $this->actingAs($admin, 'api')
            ->getJson(route('admin.loan-application.show', ['loanApplication' => 'testtetste']))
            ->assertStatus(ApiResponse::SERVER_ERROR)
            ->assertJson([
                'error' => [
                    'message' => [
                        'Loan Application not found'
                    ]
                ]
            ]);
    }

    public function test_verifyLoan_expectExceptionLoanAlreadyProceed()
    {
        $loanApplication = LoanApplication::factory()->create([
            'user_id' => $this->user->id,
            'status' => LoanApplication::STATUS['APPROVED']
        ]);

        $admin = User::factory([
            'role' => User::ROLE['ADMIN']
        ])->create();

        $this->actingAs($admin, 'api')
            ->postJson(route('admin.verify-loan-application', ['loanApplication' => $loanApplication]), [
                'is_approve' => true
            ])
            ->assertStatus(ApiResponse::BAD_REQUEST)
            ->assertJson([
                'error' => [
                    'message' => [
                        'Loan was already processed'
                    ]
                ]
            ]);
    }

    public function test_verifyLoan_expectSuccessLoanApplicationApproved()
    {
        $loanApplication = LoanApplication::factory()->create([
            'user_id' => $this->user->id,
            'tenure' => 5
        ]);

        $admin = User::factory([
            'role' => User::ROLE['ADMIN']
        ])->create();

        $this->actingAs($admin, 'api')
            ->postJson(route('admin.verify-loan-application', ['loanApplication' => $loanApplication]), [
                'is_approve' => true
            ])
            ->assertOk()
            ->assertJson([
                'data' => [
                    'message' => [
                        'Loan process completed'
                    ]
                ]
            ]);

        $this->assertDatabaseHas('loan_applications', ['status' => LoanApplication::STATUS['APPROVED']]);
        $this->assertCount(5, LoanRepayment::whereLoanApplicationId($loanApplication->id)->get());
    }

    public function test_verifyLoan_expectSuccessLoanApplicationRejected()
    {
        $loanApplication = LoanApplication::factory()->create([
            'user_id' => $this->user->id,
            'tenure' => 5
        ]);

        $admin = User::factory([
            'role' => User::ROLE['ADMIN']
        ])->create();

        $this->actingAs($admin, 'api')
            ->postJson(route('admin.verify-loan-application', ['loanApplication' => $loanApplication]), [
                'is_approve' => false
            ])
            ->assertOk()
            ->assertJson([
                'data' => [
                    'message' => [
                        'Loan process completed'
                    ]
                ]
            ]);

        $this->assertDatabaseHas('loan_applications', ['status' => LoanApplication::STATUS['REJECTED']]);
        $this->assertCount(0, LoanRepayment::whereLoanApplicationId($loanApplication->id)->get());
    }
}
