<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
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
        'department_name',
        'enabled'
    ];

    /**
     * Relation belongs to branch
     *
     * @return mixed
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Has many relation with designations
     *
     * @return mixed
     */
    public function designations()
    {
        return $this->hasMany(Designation::class);
    }

    /**
     * Has many relation with notices
     *
     * @return mixed
     */
    public function notices()
    {
        return $this->hasMany(Notice::class);
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

    /**
     * Has many relation with attendances
     *
     * @return mixed
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Has many relation with transfers
     *
     * @return mixed
     */
    public function transfers()
    {
        return $this->hasMany(Transfer::class);
    }
}
