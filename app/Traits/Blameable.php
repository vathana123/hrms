<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static void creating(\Closure $callback)
 * @method static void updating(\Closure $callback)
 * @method static void deleting(\Closure $callback)
 *
 * @mixin Model
 */
trait Blameable
{
    protected static function bootBlameable(): void
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->user()->username;
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->user()->username;
            }
        });

        static::deleting(function ($model) {
            if (
                auth()->check() &&
                method_exists($model, 'isForceDeleting') &&
                ! $model->isForceDeleting()
            ) {
                $model->deleted_by = auth()->user()->username;
                $model->saveQuietly();
            }
        });
    }
}
