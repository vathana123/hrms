<?php

namespace App\Models;

use App\Enums\LookupValueType;
use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LookupValue extends Model
{
    use Blameable, HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'short_name',
        'local_name',
        'type',
        'display_ordering',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'type' => LookupValueType::class,
    ];
}
