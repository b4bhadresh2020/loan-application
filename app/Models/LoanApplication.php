<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class LoanApplication extends Model
{
    use HasFactory;

    const STATUS = [
        'APPLIED'  => 'applied',
        'APPROVED' => 'approved',
        'REJECTED' => 'rejected',
        'CLOSED' => 'closed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function loanRepayments()
    {
        return $this->hasMany(LoanRepayment::class, 'loan_application_id');
    }

    public function scopeActive(Builder $query)
    {
        $query->where('status', self::STATUS['APPROVED']);
    }
}
