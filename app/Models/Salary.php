<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salary extends Model
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
        'parent_id',
        'basic_salary',
        'salary_name',
        'house_rent',
        'house_rent_amount',
        'medical_allowance',
        'medical_allowance_amount',
        'conveyance_allowance',
        'conveyance_allowance_amount',
        'food_allowance',
        'food_allowance_amount',
        'communication_allowance',
        'communication_allowance_amount',
        'other',
        'other_amount',
        'enabled'
    ];

    /**
     * Relation belongs to company
     *
     * @return mixed
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
