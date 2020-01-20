<?php

namespace App\Http\Controllers;

use App\OrderType;
use Illuminate\Http\Request;

class OrderTypeController extends Controller
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
        $ordertypes = OrderType::all();
        return view('admin.ordertype.index',compact('ordertypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        return view('admin.ordertype.add');
    }

    public function create(Request $request)
    {
        $orderType = new OrderType();
        $orderType->name = $request->name;
        $orderType->value = $request->code;
        $orderType->save();

        return redirect()->route('ordertype')->with('success', true)->with('message','Order Type created successfully!');
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
        $ordertype = OrderType::find($id);
        return view('admin.ordertype.edit',compact('ordertype'));
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
        $orderType = OrderType::find($request->id);
        $orderType->name = $request->name;
        $orderType->value = $request->code;
        $orderType->save();
        return redirect()->route('ordertype')->with('success', true)->with('message','Order Type updated successfully!');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        OrderType::destroy($request->id);
        return redirect()->route('ordertype');
    }
}
