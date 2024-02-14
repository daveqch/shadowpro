<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Designation extends Model
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
        'designation_name',
        'enabled'
    ];

    /**
     * Relation belongs to department
     *
     * @return mixed
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
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
