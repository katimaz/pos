<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class TemplateOrderSubProduct extends Model
{
    public static function boot()
    {
        parent::boot();
        static::creating(function($model)
        {
            $model->created_by = Auth::user()->name;
            $model->updated_by = Auth::user()->name;
        });
        static::updating(function($model)
        {
            $model->updated_by = Auth::user()->name;
        });
    }
}
