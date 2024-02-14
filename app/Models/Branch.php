<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Branch extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'location_id',
        'branch_name',
        'note',
        'enabled'
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
     * Relation belongs to location
     *
     * @return mixed
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Has many relation with employees
     *
     * @return mixed
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    /*
     * Has many relation with complains
     */
    public function complains()
    {
        return $this->hasMany(Complain::class);
    }

    /*
     * Has many relation with warnings
     */
    public function warnings()
    {
        return $this->hasMany(Complain::class);
    }

    /*
     * Has many relation with attendances
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /*
     * Has many relation with awards
     */
    public function awards()
    {
        return $this->hasMany(Award::class);
    }

    /*
     * Has many relation with resignations
     */
    public function resignations()
    {
        return $this->hasMany(Resignation::class);
    }

    /*
     * Has many relation with terminations
     */
    public function terminations()
    {
        return $this->hasMany(Termination::class);
    }

    /*
    * Has many relation with transfers
    */
    public function transfers()
    {
        return $this->hasMany(Transfer::class);
    }

    /*
    * Has many relation with loans
    */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Has many relation with loan pay
     *
     * @return mixed
     */
    public function loanpays()
    {
        return $this->hasMany(LoansPay::class);
    }

    /**
     * Has many relation with advanceSalary
     *
     * @return mixed
     */
    public function advanceSalary()
    {
        return $this->hasMany(AdvanceSalary::class);
    }
}
