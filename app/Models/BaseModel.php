<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BaseModel extends Model
{
    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_datetime = current_datetime();
            $model->created_by_uid = Auth::user()->id;
            return true;
        });

        static::updating(function ($model) {
            $model->updated_datetime = current_datetime();
            $model->updated_by_uid = Auth::user()->id;
            return true;
        });
    }
}
