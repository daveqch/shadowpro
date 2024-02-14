<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Policy extends Model
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
        'title',
        'description',
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
