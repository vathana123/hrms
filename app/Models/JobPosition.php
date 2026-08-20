<?php

namespace App\Models;

use App\Enums\LookupValueType;
use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobPosition extends Model
{
    use Blameable, HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'job_level_id',
        'name',
        'short_name',
        'local_name',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function jobLevel()
    {
        return $this->belongsTo(LookupValue::class, 'job_level_id')->where('type', LookupValueType::JOB_LEVEL);
    }
}
