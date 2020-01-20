<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Customer extends Model
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

    public static function createCustomer($request){

        $customer = new Customer();
        $customer->country_name = $request->customer_country_name;
        $customer->name = $request->customer_name;
        $customer->address = $request->customer_address;
        $customer->delivery_address = $request->customer_delivery_address;
        $customer->phone = $request->customer_phone;
        $customer->remark = $request->customer_remark;
        $customer->save();

        return $customer->id;
    }
}
