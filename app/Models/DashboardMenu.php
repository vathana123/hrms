<?php

namespace App\Models;

use App\Enums\MenuType;
use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DashboardMenu extends Model
{
    use Blameable, HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'local_name',
        'icon',
        'permission',
        'route_url',
        'open_in_new_tab',
        'type',
        'display_ordering',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'type' => MenuType::class,
    ];
}
