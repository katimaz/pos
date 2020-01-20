<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class Product extends Model
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

    public static function getProducts($id){
        $products = DB::table('products')
            ->join('categories','products.category_id','categories.id')
            ->select('products.*','categories.name as category_name')
            ->get();

        return $products;
    }
}
