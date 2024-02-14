<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvanceSalary extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'company_id',
        'branch_id',
        'employee_id',
        'reason',
        'date',
        'amount',
        'status',
        'remarks'
    ];

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
