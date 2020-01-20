<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Product;
use App\ProductDetails;
use DB;
use Response;
use Arr;

class ProductController extends Controller
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
        $products = DB::table('products')
            ->join('categories','products.category_id','categories.id')
            ->select('products.*','categories.name as category_name')
            ->get();
        return view('admin.product.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        $categories = Category::all();
        return view('admin.product.add',compact('categories'));
    }

    public function create(Request $request)
    {
        $product = new Product;
        $product->category_id = $request->category_id;
        $product->name = $request->name;
        $product->model_no = $request->model_no;
        $product->price = $request->price;
        $product->maintenance_period = $request->maintenance_period;
        $product->device_check = $request->device_check;
        $product->save();

        return redirect()->route('product')->with('success', true)->with('message','Product created successfully!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        //
    }

    public function getProductByCategoryId($id)
    {
        $products = DB::table('products')
            ->join('categories','products.category_id','categories.id')
            ->select('products.*','categories.name as category_name')
            ->where('products.category_id','=',$id)
            ->get();

        return Response::json(array('success'=>true,'result'=>$products));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::all();

        $product = DB::table('products')
            ->join('categories','products.category_id','categories.id')
            ->where('products.id',$id)
            ->select('products.*','categories.name as category_name')
            ->first();

        $productDetails = DB::table('product_details')
            ->select('product_id', DB::raw('SUM(purchase_price) as purchase_price'),
                DB::raw('SUM(purchase_quantity) as purchase_quantity'),
                DB::raw('ROUND((SUM(purchase_quantity)/SUM(purchase_price)),2) as average')
            )
            ->groupBy('product_id')
            ->where('product_details.product_id',$product->id)
            ->get();


        return view('admin.product.edit',compact('product','categories'))->with('success', true)->with('message','Product updated successfully!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request)
    {
        $product = Product::find($request->id);
        $product->category_id = $request->category_id;
        $product->name = $request->name;
        $product->model_no = $request->model_no;
        $product->price = $request->price;
        $product->maintenance_period = $request->maintenance_period;
        $product->device_check = $request->device_check;
        $product->sale_check = $request->sale_check;
        $product->save();
        return redirect()->route('product');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Product::destroy($request->id);
        return redirect()->route('product');
    }

    public function getProductDetails(Request $request){

        $productDetails = DB::table('product_details')
            ->select(DB::raw('SUM(purchase_price) as purchase_price'),
                DB::raw('SUM(purchase_quantity) as purchase_quantity'),
                DB::raw('ROUND((SUM(purchase_price)/SUM(purchase_quantity)),3) as average')
            )
            ->where('product_details.product_id',$request->id)
            ->get();

        $totalSoldProducts= DB::table('order_products')
            ->select(DB::raw('SUM(product_quantity) as product_quantity'))
            ->where('order_products.product_id','=',$request->id)
            ->get();

        $productDetails = $productDetails->concat($totalSoldProducts);

        return Response::json(array('success'=>true,'data'=>$productDetails));
    }
    public function addDetails(Request $request)
    {
        $productDetails = new ProductDetails();
        $productDetails->product_id = $request->id;
        $productDetails->purchase_date = $request->data[1];
        $productDetails->purchase_name = $request->data[2];
        $productDetails->purchase_price = $request->data[3];
        $productDetails->purchase_quantity = $request->data[4];
        $productDetails->save();

        return Response::json(array('success'=>true,'data'=>$productDetails->id));
    }

    public function getDetails(Request $request)
    {
        $productDetails = DB::table('product_details')
            ->where('product_id','=',$request->id)
            ->get();

        $productDetailsArray = [];
        if(!empty($productDetails)) {
            for ($i = 0; $i < count($productDetails); $i++) {
                $temp = [];
                array_push($temp, $productDetails[$i]->id);
                array_push($temp, $productDetails[$i]->purchase_date);
                array_push($temp, $productDetails[$i]->purchase_name);
                array_push($temp, $productDetails[$i]->purchase_price);
                array_push($temp, $productDetails[$i]->purchase_quantity);
                array_push($productDetailsArray, $temp);
            }
        }

        return Response::json(array('success'=>true,'data'=>$productDetailsArray));
    }

    public function deleteDetails(Request $request)
    {
        ProductDetails::destroy($request->id);
        return Response::json(array('success'=>true));
    }

    public function editDetails(Request $request)
    {
        $productDetails = ProductDetails::find($request->data[0]);
        $productDetails->purchase_date = $request->data[1];
        $productDetails->purchase_name = $request->data[2];
        $productDetails->purchase_price = $request->data[3];
        $productDetails->purchase_quantity = $request->data[4];
        $productDetails->save();

        return Response::json(array('success'=>true));
    }
}
