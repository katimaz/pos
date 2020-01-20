<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class OrderProduct extends Model
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

    public static function createOrderProduct($request,$order_id,$i){

        $orderProduct = new OrderProduct();
        $orderProduct->order_id = $order_id;
        $orderProduct->product_id = $request->product_id[$i];
        $orderProduct->product_name = $request->product_name[$i];
        $orderProduct->product_model_no = $request->product_model_no[$i];
        $orderProduct->product_price = $request->product_price[$i];
        $orderProduct->product_quantity = $request->product_quantity[$i];
        $orderProduct->product_total_price = $request->product_total_price[$i];
        $orderProduct->product_serial_no = $request->product_serial_no[$i];
        $orderProduct->product_remark = $request->product_remark[$i];
        $orderProduct->save();

        return $orderProduct;
    }
}
