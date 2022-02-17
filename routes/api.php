<?php

use App\Http\Controllers\Admin\LoanApplicationController as AdminLoanApplicationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LoanApplicationController;
use App\Http\Controllers\Api\LoanRepaymentController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(
    function () {

        //Auth Routes
        Route::post('register', [AuthController::class, 'register'])->name('register');
        Route::post('login', [AuthController::class, 'login'])->name('login');

        Route::middleware('auth:api')->group(
            function () {

                //Logout
                Route::get('logout', [AuthController::class, 'logout'])->name('logout');

                Route::middleware('user-access')->group(
                    function () {

                        //User Profile
                        Route::get('profile', [UserController::class, 'profile'])->name('user.profile');

                        //Loan Applications
                        Route::resource('loan-applications', LoanApplicationController::class);

                        //Loan repayments or EMI
                        Route::get('repayments', [LoanRepaymentController::class, 'index'])->name('repayments');

                        //current EMI
                        Route::post('repayments/{loanRepayment}', [LoanRepaymentController::class, 'repayLoanEmi'])->name('repay-loan-emi');
                    }
                );

                //admin
                Route::middleware('admin-access')->prefix('admin')->namespace('Admin')->group(function () {
                    //Loan applications detail
                    Route::get('/loan-applications/{loanApplication}', [AdminLoanApplicationController::class, 'show'])->name('admin.loan-application.show');

                    // Change loan application status
                    Route::post('verify/loan-applications/{loanApplication}', [AdminLoanApplicationController::class, 'verifyLoanApplication'])->name('admin.verify-loan-application');
                });
            }
        );
    }
);
