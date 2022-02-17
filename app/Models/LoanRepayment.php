<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class LoanRepayment extends Model
{
    use HasFactory;

    const STATUS = [
        'PENDING'  => 'pending',
        'PAID' => 'paid',
        'PARTIALLY_PAID' => 'partially_paid',
    ];

    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class, 'loan_application_id');
    }
}
