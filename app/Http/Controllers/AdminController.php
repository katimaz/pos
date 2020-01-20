<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class AdminController extends Controller
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
        $total_revenue = DB::table('orders')->sum('total_price');
        $total_month_revenue = DB::table('orders')->whereMonth('created_at',Carbon::now()->month)->sum('total_price');
        $total_week_revenue = DB::table('orders')->where('created_at','>=',Carbon::now()->subDays(7))->sum('total_price');
        $total_day_revenue = DB::table('orders')->where('created_at','>=',Carbon::today()->toDateString())->sum('total_price');
        $product_count = DB::table('products')->count();
        $customer_count = DB::table('customers')->count();
        $order_count = DB::table('orders')->count();

        $average_revenue = $total_revenue/$order_count;

        $days = Carbon::now()->daysInMonth;
        if($days == 30){
            $label = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30"];
        }else{
            $label = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31"];
        }
        $data = DB::table('orders')
            ->whereMonth('created_at',Carbon::now()->month)
            ->select(DB::raw('DATE_FORMAT(created_at,"%d %b %Y") as date'),DB::raw('sum(total_price) as total_price'))
            ->groupBy('date')
            ->pluck('total_price','date')->all();

        return view('admin.index',compact('total_month_revenue','total_week_revenue','total_day_revenue','total_revenue','product_count','customer_count','order_count','average_revenue',"data",'label'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    public function getData(Request $request){
        $result = [];
        $label = [];
        $orderCounts = [];
        $year =Carbon::now()->year;
        $month = Carbon::now()->month;
        $days = Carbon::now()->daysInMonth;

        if($request->month != null){
            $days = Carbon::parse($request->month)->daysInMonth;
        }

        for($i = 0 ;$i < $days;$i++){
            $label[$i] = $i+1;
            $result[$i] = 0;
            $orderCounts[$i] = 0;
        }

        if($request->month != null){
            $month =Carbon::parse($request->month)->month;
        }

        if($request->year != null){
            $year =$request->year;
        }

        $data = DB::table('orders')
            ->whereMonth('created_at',$month)
            ->whereYear('created_at',$year)
            ->select(DB::raw('DATE_FORMAT(created_at,"%d %b %Y") as date'),DB::raw('sum(total_price) as total_price'))
            ->groupBy('date')
            ->pluck('total_price','date')->all();

//        $orders = DB::table('orders')
//            ->whereMonth('created_at',$month)
//            ->whereYear('created_at',$year)
//            ->select(DB::raw('DATE_FORMAT(created_at,"%d %b %Y") as date'),DB::raw('count(orders.id) as order_counts'))
//            ->groupBy('date')
//            ->pluck('order_counts','date')->all();

        foreach($data as $key=> $dt) {
            foreach ($result as $index => $value) {
                if (substr($key, 0, 2) == sprintf('%02d', $index + 1)) {
                    $result[$index] = $dt;
                }
            }
        }

//        foreach($orders as $key=> $dt) {
//            foreach ($orderCounts as $index => $value) {
//                if (substr($key, 0, 2) == sprintf('%02d', $index + 1)) {
//                    $orderCounts[$index] = $dt;
//                }
//            }
//        }

        $resultSet = [];
        $resultSet[0] = $result;
        $resultSet[1] = $label;
//        $resultSet[2] = $orderCounts;

        return response()->json($resultSet);
    }

    public function getMonthData(Request $request){

        $result = ["0", "0", "0", "0", "0","0", "0", "0", "0", "0", "0", "0"];
        $year =Carbon::now()->year;

        if($request->year != null){
            $year =$request->year;
        }

        $data = DB::table('orders')
            ->whereYear('created_at',$year)
            ->select(DB::raw('DATE_FORMAT(created_at,"%c") as date'),DB::raw('sum(total_price) as total_price'))
            ->groupBy('date')
            ->pluck('total_price','date')->all();

        foreach($data as $key=> $dt) {
            foreach ($result as $index => $value) {
                if (substr($key, 0, 2) == sprintf('%02d', $index + 1)) {
                    $result[$index] = $dt;
                }
            }
        }

        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
