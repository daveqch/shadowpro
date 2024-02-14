<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notice extends Model
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
        'branch_id',
        'title',
        'description',
        'start_date',
        'end_date',
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
     * Relation belongs to branch
     *
     * @return mixed
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
