<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'company_id',
        'branch_id',
        'employee_id',
        'interest',
        'name',
        'receive_loan',
        'loan_date',
        'from_date',
        'loan_amount',
        'loan_installment',
        'loan_installment_amount',
        'ad_type',
        'give_type',
        'bank_id',
        'bank_name',
        'branch_name',
        'cheque_id',
        'cheque_date',
        'loan_status',
        'note'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }


    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function loansPay(): HasMany
    {
        return $this->hasMany(LoansPay::class);
    }
}
