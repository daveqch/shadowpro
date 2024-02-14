<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoansPay extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'employee_id',
        'branch_id',
        'company_id',
        'description',
        'pay_amount',
        'pay_date'
    ];

    /**
     * Relation belongs to company
     *
     * @return mixed
     */
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    /**
     * Relation belongs to company
     *
     * @return mixed
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Relation belongs to branch
     *
     * @return mixed
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Relation belongs to employee
     *
     * @return mixed
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
