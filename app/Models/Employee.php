<?php

namespace App\Models;

use App\Models\Bank;
use App\Models\User;
use App\Models\Salary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'user_id',
        'bank_id',
        'company_id',
        'branch_id',
        'department_id',
        'designation_id',
        'e_id',
        'name',
        'photo',
        'joining_date',
        'birth_date',
        'gender',
        'phone',
        'present_address',
        'permanent_address',
        'email',
        'password',
        'salary_id',
        'job_type',
        'emergency_contact_name',
        'emergency_contact_number',
        'emergency_contact_relation',
        'emergency_contact_note',
        'resume',
        'offer_letter',
        'joining_letter',
        'contract_and_agreement',
        'identity_proof',
        'note',
        'enabled'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }

    public function salary(): BelongsTo
    {
        return $this->belongsTo(Salary::class);
    }

    /**
     * Relation hasMany to attendance
     *
     * @return mixed
     */
    public function attendance(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
