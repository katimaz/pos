<?php

namespace App\Http\Controllers;

use App\Category;
use App\TemplateOrder;
use App\OrderType;
use App\TemplateOrderProduct;
use App\TemplateOrderSubProduct;
use Illuminate\Http\Request;
use App\Customer;
use App\OrderSubProduct;
use App\OrderProduct;
use DB;
use App\Country;
class TemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $templates = DB::table('template_orders')->get();

        return view('admin.template.index',compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        $products = DB::table('products')
            ->join('categories','products.category_id','categories.id')
            ->select('products.*','categories.name as category_name')
            ->orderBy('categories.priority','asc')
            ->get();
        $customers = Customer::all();
        $countries = Country::all();
        $order_types = OrderType::all();
        $categories = Category::all();

        return view('admin.template.add',compact('customers','products','countries','order_types','categories'));
    }

    public function create(Request $request)
    {
        $order = new TemplateOrder();
        $order->template_name = $request->template_name;
        $order->template_remark = $request->template_remark;
        $order->order_type = $request->invoice_type;
        $order->customer_id = $request->customer_id;
        $order->customer_country_name = $request->customer_country_name;
        $order->customer_name = $request->customer_name;
        $order->customer_address = $request->customer_address;
        $order->customer_delivery_address = $request->customer_delivery_address;
        $order->customer_phone = $request->customer_phone;
        $order->customer_remark = $request->customer_remark;
        $order->total_price = $request->order_total_price;
        $order->save();

        if(!empty($request->ui_product_id)) {
            for ($i = 0; $i < count($request->ui_product_id); $i++) {
                $orderProduct = new TemplateOrderProduct();
                $orderProduct->order_id = $order->id;
                $orderProduct->product_id = $request->product_id[$i];
                $orderProduct->product_name = $request->product_name[$i];
                $orderProduct->product_model_no = $request->product_model_no[$i];
                $orderProduct->product_price = $request->product_price[$i];
                $orderProduct->product_quantity = $request->product_quantity[$i];
                $orderProduct->product_total_price = $request->product_total_price[$i];
                $orderProduct->product_serial_no = $request->product_serial_no[$i];
                $orderProduct->product_remark = $request->product_remark[$i];
                $orderProduct->save();

                if(!empty($request->ui_sub_product_id)) {
                    for ($y = 0; $y < count($request->ui_sub_product_id); $y++) {
                        if ($request->ui_product_id[$i] == $request->ui_sub_product_id[$y]) {
                            $orderSubProduct = new TemplateOrderSubProduct();
                            $orderSubProduct->order_product_id = $orderProduct->id;
                            $orderSubProduct->sub_product_id = $request->sub_product_id[$y];
                            $orderSubProduct->sub_product_name = $request->sub_product_name[$y];
                            $orderSubProduct->sub_product_quantity = $request->sub_product_quantity[$y];
                            $orderSubProduct->sub_product_price = $request->sub_product_price[$y];
                            $orderSubProduct->sub_product_total_price = $request->sub_product_total_price[$y];
                            $orderSubProduct->sub_product_model_no = $request->sub_product_model_no[$y];
                            $orderSubProduct->sub_product_serial_no = $request->sub_product_serial_no[$y];
                            $orderSubProduct->sub_product_remark = $request->sub_product_remark[$y];
                            $orderSubProduct->save();
//                    array_splice($request->sub_product_category_id,$y)
                        }
                    }
                }
            }
        }
        return redirect()->route('template')->with('success', true)->with('message','Template created successfully!');
    }

    public function edit($id)
    {
        $customers = Customer::all();
        $countries = Country::all();
        $order_types = OrderType::all();
        $categories = Category::all();

        $products = DB::table('products')
            ->join('categories','products.category_id','categories.id')
            ->select('products.*','categories.name as category_name','products.category_id as product_category_id')
            ->orderBy('categories.priority','asc')
            ->get();

        $orders = DB::table('template_orders')
            ->join('template_order_products','template_order_products.order_id','template_orders.id')
            ->join('products','products.id','template_order_products.product_id')
            ->join('categories','categories.id','products.category_id')
            ->where('template_orders.id',$id)
            ->select('template_orders.id as ordersid' ,'template_orders.*','template_order_products.id as order_productsid', 'template_order_products.*','categories.name as category_name','products.category_id as product_category_id')
            ->orderBy('categories.priority','asc')
            ->get();

        $orderProductsIds = DB::table('template_orders')
            ->join('template_order_products','template_order_products.order_id','template_orders.id')
            ->where('template_orders.id',$id)
            ->select('template_order_products.id')
            ->get();

        $orderProductsIdArray = [];
        foreach ($orderProductsIds as $orderProductsId) {
            array_push($orderProductsIdArray,$orderProductsId->id);
        }

        $orderSubProducts = DB::table('template_order_sub_products')
            ->join('products','products.id','template_order_sub_products.sub_product_id')
            ->join('categories','categories.id','products.category_id')
            ->whereIn('template_order_sub_products.order_product_id',$orderProductsIdArray)
            ->select('template_order_sub_products.*','categories.name as category_name','products.category_id as sub_product_category_id')
            ->get();
        return view('admin.template.edit',compact('orders','customers','products','orderSubProducts','countries','order_types','categories'));
    }

    public function update(Request $request)
    {
        $order = TemplateOrder::find($request->order_id);
        $order->template_name = $request->template_name;
        $order->template_remark = $request->template_remark;
        $order->order_type = $request->invoice_type;
        $order->customer_id = $request->customer_id;
        $order->customer_country_name = $request->customer_country_name;
        $order->customer_name = $request->customer_name;
        $order->customer_address = $request->customer_address;
        $order->customer_delivery_address = $request->customer_delivery_address;
        $order->customer_phone = $request->customer_phone;
        $order->customer_remark = $request->customer_remark;
        $order->total_price = $request->order_total_price;
        $order->save();

        if(!empty($request->ui_product_id)) {
            for ($i = 0; $i < count($request->ui_product_id); $i++) {
                if ($request->order_productsid[$i] == null) {
                    $orderProduct = new TemplateOrderProduct();
                    $orderProduct->order_id = $order->id;
                    $orderProduct->product_id = $request->product_id[$i];
                    $orderProduct->product_name = $request->product_name[$i];
                    $orderProduct->product_model_no = $request->product_model_no[$i];
                    $orderProduct->product_price = $request->product_price[$i];
                    $orderProduct->product_quantity = $request->product_quantity[$i];
                    $orderProduct->product_total_price = $request->product_total_price[$i];
                    $orderProduct->product_serial_no = $request->product_serial_no[$i];
                    $orderProduct->product_remark = $request->product_remark[$i];
                    $orderProduct->save();

                    if(!empty($request->ui_sub_product_id)) {
                        for ($y = 0; $y < count($request->ui_sub_product_id); $y++) {
                            if ($request->ui_product_id[$i] == $request->ui_sub_product_id[$y]) {
                                $orderSubProduct = new TemplateOrderSubProduct();
                                $orderSubProduct->order_product_id = $orderProduct->id;
                                $orderSubProduct->sub_product_id = $request->sub_product_id[$y];
                                $orderSubProduct->sub_product_name = $request->sub_product_name[$y];
                                $orderSubProduct->sub_product_quantity = $request->sub_product_quantity[$y];
                                $orderSubProduct->sub_product_price = $request->sub_product_price[$y];
                                $orderSubProduct->sub_product_total_price = $request->sub_product_total_price[$y];
                                $orderSubProduct->sub_product_model_no = $request->sub_product_model_no[$y];
                                $orderSubProduct->sub_product_serial_no = $request->sub_product_serial_no[$y];
                                $orderSubProduct->sub_product_remark = $request->sub_product_remark[$y];
                                $orderSubProduct->save();
                            }
                        }
                    }
                } else {
                    $orderProduct = TemplateOrderProduct::find($request->order_productsid[$i]);
                    $orderProduct->order_id = $order->id;
                    $orderProduct->product_id = $request->product_id[$i];
                    $orderProduct->product_name = $request->product_name[$i];
                    $orderProduct->product_model_no = $request->product_model_no[$i];
                    $orderProduct->product_price = $request->product_price[$i];
                    $orderProduct->product_quantity = $request->product_quantity[$i];
                    $orderProduct->product_total_price = $request->product_total_price[$i];
                    $orderProduct->product_serial_no = $request->product_serial_no[$i];
                    $orderProduct->product_remark = $request->product_remark[$i];
                    $orderProduct->save();

                    if(!empty($request->ui_sub_product_id)) {
                        for ($y = 0; $y < count($request->ui_sub_product_id); $y++) {
                            if ($request->ui_product_id[$i] == $request->ui_sub_product_id[$y]) {
                                if ($request->sub_product_db_id[$y] == null) {
                                    $orderSubProduct = new TemplateOrderSubProduct();
                                    $orderSubProduct->order_product_id = $orderProduct->id;
                                    $orderSubProduct->sub_product_id = $request->sub_product_id[$y];
                                    $orderSubProduct->sub_product_name = $request->sub_product_name[$y];
                                    $orderSubProduct->sub_product_quantity = $request->sub_product_quantity[$y];
                                    $orderSubProduct->sub_product_price = $request->sub_product_price[$y];
                                    $orderSubProduct->sub_product_total_price = $request->sub_product_total_price[$y];
                                    $orderSubProduct->sub_product_model_no = $request->sub_product_model_no[$y];
                                    $orderSubProduct->sub_product_serial_no = $request->sub_product_serial_no[$y];
                                    $orderSubProduct->sub_product_remark = $request->sub_product_remark[$y];
                                    $orderSubProduct->save();
                                } else {
                                    $orderSubProduct = TemplateOrderSubProduct::find($request->sub_product_db_id[$y]);
                                    $orderSubProduct->order_product_id = $orderProduct->id;
                                    $orderSubProduct->sub_product_id = $request->sub_product_id[$y];
                                    $orderSubProduct->sub_product_name = $request->sub_product_name[$y];
                                    $orderSubProduct->sub_product_quantity = $request->sub_product_quantity[$y];
                                    $orderSubProduct->sub_product_price = $request->sub_product_price[$y];
                                    $orderSubProduct->sub_product_total_price = $request->sub_product_total_price[$y];
                                    $orderSubProduct->sub_product_model_no = $request->sub_product_model_no[$y];
                                    $orderSubProduct->sub_product_serial_no = $request->sub_product_serial_no[$y];
                                    $orderSubProduct->sub_product_remark = $request->sub_product_remark[$y];
                                    $orderSubProduct->save();
                                }
                            }
                        }
                    }
                }
            }
        }
        return redirect()->route('template')->with('success', true)->with('message','Template updated successfully!');
    }

    public function destroy(Request $request)
    {
        $orderProductsIds = DB::table('template_order_products')
            ->join('template_orders','template_order_products.order_id','template_orders.id')
            ->where('template_orders.id',$request->id)
            ->select('template_order_products.id')
            ->get();

        $orderProductsIdArray = [];
        foreach ($orderProductsIds as $orderProductsId) {
            array_push($orderProductsIdArray,$orderProductsId->id);
        }

        DB::table('template_order_sub_products')
            ->whereIn('template_order_sub_products.order_product_id',$orderProductsIdArray)
            ->delete();

        DB::table('template_order_products')
            ->join('template_orders','template_order_products.order_id','template_orders.id')
            ->where('template_orders.id',$request->id)
            ->delete();

        TemplateOrder::destroy($request->id);

        return redirect()->route('template')->with('success', true)->with('message','Template delete successfully!');
    }
}
