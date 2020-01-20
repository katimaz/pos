<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class OrderSubProduct extends Model
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

    public static function createOrderSubProduct($request,$order_product_id,$y){

        $orderSubProduct = new OrderSubProduct();
        $orderSubProduct->order_product_id = $order_product_id;
        $orderSubProduct->sub_product_id = $request->sub_product_id[$y];
        $orderSubProduct->sub_product_name = $request->sub_product_name[$y];
        $orderSubProduct->sub_product_quantity = $request->sub_product_quantity[$y];
        $orderSubProduct->sub_product_price = $request->sub_product_price[$y];
        $orderSubProduct->sub_product_total_price = $request->sub_product_total_price[$y];
        $orderSubProduct->sub_product_model_no = $request->sub_product_model_no[$y];
        $orderSubProduct->sub_product_serial_no = $request->sub_product_serial_no[$y];
        $orderSubProduct->sub_product_remark = $request->sub_product_remark[$y];
        $orderSubProduct->save();

        return $orderSubProduct;
    }
}
