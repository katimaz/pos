<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Order extends Model
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

    public static function createOrder($request,$customer_id){

        $order = new Order;
        $order->order_type = $request->invoice_type;
        $order->order_no = $request->invoice_number;
        $order->order_date = date('Y-m-d',strtotime($request->invoice_date));
        $order->separate = $request->separate_check;
        $order->referral = $request->referral;
        $order->customer_id = $customer_id;
        $order->customer_country_name = $request->customer_country_name;
        $order->customer_name = $request->customer_name;
        $order->customer_address = $request->customer_address;
        $order->customer_delivery_address = $request->customer_delivery_address;
        $order->customer_phone = $request->customer_phone;
        $order->customer_remark = $request->customer_remark;
        $order->total_price = $request->order_total_price;
        $order->order_currency = $request->currency_name;
        $order->order_currency_ratio = $request->currency_ratio;
        $order->save();

        return $order;

    }
}
