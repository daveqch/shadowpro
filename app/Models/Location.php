<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
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
        'location_name',
        'address',
        'city',
        'state',
        'zip',
        'country',
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

    /**
     * Has one relation with branches
     *
     * @return mixed
     */
    public function branches()
    {
        return $this->hasOne(Branch::class);
    }
}
