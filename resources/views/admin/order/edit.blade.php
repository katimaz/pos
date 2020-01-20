@extends('layouts.content')

@section('css')
    @parent
    <link href="{{ asset('vendor/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet">
    <link href="{{ asset('vendor/datepicker/datepicker.min.css')}}" rel="stylesheet">
    <style>
        .droplist-style{
            color: #6e707e !important;
            background-color: #fff !important;
            background-clip: padding-box !important;
            border: 1px solid #d1d3e2 !important;
            border-radius: 0.35rem !important;
        }
        .non-display{
            display:none;
        }

        @media only screen and (min-width: 575px)  {
            .separate{
                position: absolute;
                bottom: 0;
                right: 0;
            }
        }
    </style>
@stop

@section('js')
    @parent
    <script src="{{ asset('vendor/bootstrap-select/bootstrap-select.min.js')}}" defer></script>
    <script src="{{ asset('vendor/datepicker/datepicker.min.js')}}"></script>
    <script>
        $selectProductOption = "{{__('product.select.option')}}"
        var x = {{count($orderSubProducts)+1}}
        var i = {{count($orders)}}
        var y = {{count($orders)}}
        if(y == 1){
            $('#minus_product').hide();
        }
        var order_date = '{{$orders[0]->order_date}}';
    </script>
    <script src="{{ asset('js/order.js')}}"></script>
@stop

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">{{__('order.edit.title')}}</h1>
        <p class="mb-4"></p>

        <form method="POST" action="{{Route::current()->getName() == "order.edit"?route('order.update'):route('order.create')}}">
            @csrf
            <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <select id="invoice-type-select" class="selectpicker show-tick form-control" data-size="5" data-style="droplist-style" title="{{__('order.select.option')}}" name="invoice_type">
                        @foreach($order_types as $order_type)
                            @if($orders[0]->order_type == $order_type->value)
                                <option value="{{$order_type->value}}" selected>{{$order_type->name}}</option>
                            @else
                                <option value="{{$order_type->value}}">{{$order_type->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <select id="currency-select" class="selectpicker show-tick form-control" data-size="5" data-style="droplist-style" title="{{__('currency.select.option')}}" name="currency_name" required>
                        @foreach($currencies as $currency)
                            @if($orders[0]->order_currency == $currency->en_name)
                                <option currency_ratio="{{$currency->ratio}}" value="{{$currency->en_name}}" selected>{{$currency->name."(".$currency->en_name.")"}}</option>
                            @else
                                <option currency_ratio="{{$currency->ratio}}" value="{{$currency->en_name}}">{{$currency->name."(".$currency->en_name.")"}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2 mb-3 mb-sm-0">
                </div>
                <input type="hidden" id="currency_ratio" name="currency_ratio" value="{{$orders[0]->order_currency_ratio}}"/>
            </div>
            <div class="form-group row">
                <div class="col-sm-4 mb-3 mb-sm-0">
                    <input id="invoice_date" name="invoice_date" class="input-material form-control" value="{{$orders[0]->order_date}}">
                    <label for="invoice_date" class="input-label">{{__('order.invoice.date')}}</label>
                </div>
                <div class="col-sm-4 mb-3 mb-sm-0">
                    @if(is_null($orders[0]->order_no))
                        <input type="text" class="input-material form-control" name="invoice_number" id="invoice_number" placeholder="Enter Invoice Number" value="{{$max_order_no == null?'50000':$max_order_no+1}}" required>
                    @else
                        <input type="text" class="input-material form-control" name="invoice_number" id="invoice_number" placeholder="Enter Invoice Number" value="{{$orders[0]->order_no}}" required>
                    @endif

                    <label for="invoice_number" class="input-label">{{__('order.invoice.number')}}</label>
                </div>
                <div class="col-sm-2 mb-3 mb-sm-0">
                    <input type="text" class="input-material form-control" name="referral" id="referral" placeholder="Enter Hospital Referral" value="{{$orders[0]->referral}}">
                    <label for="referral" class="input-label">{{__('order.hospital.referral')}}</label>
                </div>
                <div class="col-sm-2 mb-2 mb-sm-0 separate">
                    <div class="custom-control custom-checkbox">
                        @if(is_null($orders[0]->separate))
                            <input type="checkbox" class="custom-control-input" id="separate_check" name="separate_check" value="C">
                        @else
                            <input type="checkbox" class="custom-control-input" id="separate_check" name="separate_check" value="C" checked>
                        @endif
                        <label class="custom-control-label" for="separate_check">{{__('order.separate')}}</label>
                    </div>
                </div>
            </div>
            <br/>
            <h1 class="h4 mb-2 text-gray-600">{{__('customer.title')}}</h1>
            <p class="mb-4"></p>

            <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <select id="customer-select"class="selectpicker show-tick form-control" data-size="5" data-style="droplist-style" data-live-search="true" title="{{__('customer.select.option')}}" name="customer_id">
                        @foreach($customers as $customer)
                            @if($customer->id == $orders[0]->customer_id)
                                <option customer_country_name="{{$customer->country_name}}" customer_name="{{$customer->name}}" customer_address="{{$customer->address}}" customer_delivery_address="{{$customer->delivery_address}}" customer_phone="{{$customer->phone}}" customer_remark="{{$customer->remark}}" value="{{$customer->id}}" selected>{{$customer->name}}</option>
                            @else
                                <option customer_country_name="{{$customer->country_name}}" customer_name="{{$customer->name}}" customer_address="{{$customer->address}}" customer_delivery_address="{{$customer->delivery_address}}" customer_phone="{{$customer->phone}}" customer_remark="{{$customer->remark}}" value="{{$customer->id}}">{{$customer->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <select id="country-select"class="selectpicker show-tick form-control" data-size="5" data-style="droplist-style" data-live-search="true" title="{{__('country.select.option')}}" name="customer_country_name">
                        @foreach($countries as $country)
                            @if($country->name == $orders[0]->customer_country_name)
                                <option value="{{$country->name}}" selected>{{$country->name}}</option>
                            @else
                                <option value="{{$country->name}}">{{$country->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="hidden" name="order_id" value="{{$orders[0]->ordersid}}">
                    <input type="text" class="input-material form-control" name="customer_name" id="customer_name" placeholder="Enter Name" value="{{$orders[0]->customer_name}}" required>
                    <label for="customer_name" class="input-label">{{__('customer.name')}}</label>
                </div>
                <div class="col-sm-6">
                    <input type="text" class="input-material form-control" id="customer_phone" name="customer_phone" placeholder="Enter Phone" value="{{$orders[0]->customer_phone}}" required>
                    <label for="customer_phone" class="input-label">{{__('customer.phone')}}</label>
                </div>
            </div>
            <div class="form-group">
                <input type="text" class="input-material form-control" id="customer_address" name="customer_address" placeholder="Enter Address" value="{{$orders[0]->customer_address}}" required>
                <label for="customer_address" class="input-label">{{__('customer.address')}}</label>
            </div>
            <div class="form-group" style="margin-bottom: 0rem">
                <input type="text" class="input-material form-control" id="customer_delivery_address" name="customer_delivery_address" value="{{$orders[0]->customer_delivery_address}}" placeholder="Enter Delivery Address">
                <label for="customer_delivery_address" class="input-label">{{__('customer.delivery.address')}}</label>
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox small">
                    <input type="checkbox" class="custom-control-input" id="same_address_check">
                    <label class="custom-control-label" for="same_address_check">{{__('customer.same.address')}}</label>
                </div>
            </div>
            <div class="form-group">
                <input type="text" class="input-material form-control" id="customer_remark" name="customer_remark" value="{{$orders[0]->customer_remark}}" placeholder="Enter Remark">
                <label for="customer_remark" class="input-label">{{__('customer.remark')}}</label>
            </div>
            <br/>
            <h1 class="h4 mb-2 text-gray-600">{{__('product.title')}}</h1>
            <p class="mb-4"></p>
            <a id="add" style="margin-bottom: 20px;color: white;cursor: pointer" class="btn btn-xs btn-primary"><i class="fas fa-fw fa-plus-circle"></i></a>
{{--            <a id="minus" style="margin-bottom: 20px;color: white;cursor: pointer;display:none;" class="btn btn-xs btn-danger"><i class="fas fa-fw fa-minus-circle"></i></a>--}}
            @php
                $i = count($orders)-1
            @endphp
            @foreach($orders as $order)
                <div class="form-group row">
                    <div class="col-sm-5 mb-3 mb-sm-0">
                        <select id="category-select" class="category-select select selectpicker show-tick form-control" data-size="5" data-style="droplist-style" data-live-search="true" data-show-subtext="true">
                            <option value="" selected disabled hidden>{{__('category.select.option')}}</option>
                            @foreach($categories as $category)
                                @if($category->id == $order->product_category_id)
                                    <option category_id="{{$category->id}}" value="{{$category->id}}" selected>{{$category->name}}</option>
                                @else
                                    <option category_id="{{$category->id}}" value="{{$category->id}}">{{$category->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-5 mb-3 mb-sm-0">
                        <select id="product-select" class="product-select select selectpicker show-tick form-control" data-size="5" data-style="droplist-style" data-live-search="true" data-show-subtext="true">
                            <option value="" selected disabled hidden>{{__('product.select.option')}}</option>
                            @foreach($products as $product)
                                @if($product->id == $order->product_id)
                                    <option product_id="{{$product->id}}" product_name="{{$product->name}}" product_model_no="{{$product->model_no}}" product_price="{{$product->price}}" value="{{$product->id}}" data-subtext="{{$product->category_name}}" selected>{{$product->name}}</option>
                                @elseif($product->category_id == $order->product_category_id)
                                    <option product_id="{{$product->id}}" product_name="{{$product->name}}" product_model_no="{{$product->model_no}}" product_price="{{$product->price}}" value="{{$product->id}}" data-subtext="{{$product->category_name}}">{{$product->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2 mb-3 mb-sm-0">
                        <a id="minus_product" style="margin-bottom: 20px;color: white;cursor: pointer;" class="minus_product btn btn-xs btn-danger"><i class="fas fa-fw fa-minus-circle"></i></a>
                    </div>
                </div>
                <div id="add_new_product{{$i==1?$i:''}}" class="add_product">
                    <div class="form-group row">
                        <div class="col-sm-4 mb-3 mb-sm-0" style="display: none">
                            <input type="text" class="input-material form-control" id="product_id" name="product_id[]" value="{{$order->product_id}}">
                            <!--product_id must be first one-->
                            <input type="text" class="input-material form-control" id="ui_product_id" name="ui_product_id[]" value="{{$i}}">

                        </div>
                        <div class="col-sm-4 mb-3 mb-sm-0">
                            <input type="text" class="input-material form-control" id="product_name{{$i==1?$i:''}}" name="product_name[]" placeholder="Enter Product Name" value="{{$order->product_name}}" required>
                            <label for="product_name{{$i==1?$i:''}}" class="input-label">{{__('product.name')}}</label>
                            <input type="hidden" id="order_productsid" name="order_productsid[]" value="{{$order->order_productsid}}">
                        </div>
                        <div class="col-sm-4 mb-3 mb-sm-0">
                            <input type="text" class="input-material form-control" id="product_model_no{{$i==1?$i:''}}" name="product_model_no[]" placeholder="Enter Model Number" value="{{$order->product_model_no}}">
                            <label for="product_model_no{{$i==1?$i:''}}" class="input-label">{{__('product.model.name')}}</label>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="input-material form-control product-price" id="product_price{{$i==1?$i:''}}" name="product_price[]" placeholder="Enter Price" value="{{$order->product_price}}" required>
                            <label for="product_price{{$i==1?$i:''}}" class="input-label">{{__('product.price')}}</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <input type="text" class="input-material form-control product-price" min="1" id="product_quantity{{$i==1?$i:''}}" name="product_quantity[]" placeholder="Enter Quantity Name" value="{{$order->product_quantity}}" required>
                            <label for="quantity{{$i==1?$i:''}}" class="input-label">{{__('product.quantity')}}</label>
                        </div>
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <input type="text" class="input-material form-control total-price" id="product_total_price{{$i==1?$i:''}}" name="product_total_price[]" placeholder="Enter Total Price" value="{{$order->product_total_price}}">
                            <label for="product_total_price{{$i==1?$i:''}}" class="input-label">{{__('product.total.price')}}</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            <input type="text" class="input-material form-control" id="product_serial_no{{$i==1?$i:''}}" name="product_serial_no[]" placeholder="Enter Product Serial Number" value="{{$order->product_serial_no}}">
                            <label for="product_serial_no{{$i==1?$i:''}}" class="input-label">{{__('product.serial.number')}}</label>
                        </div>
                        <div class="col-sm-5 mb-3 mb-sm-0">
                            <input type="text" class="input-material form-control" id="product_remark{{$i==1?$i:''}}" name="product_remark[]" placeholder="Enter Product Remark" value="{{$order->product_remark}}">
                            <label for="product_remark{{$i==1?$i:''}}" class="input-label">{{__('product.remark')}}</label>
                        </div>
                        @php
                            $k = 0;
                        @endphp
                        @foreach($orderSubProducts as $orderSubProduct)
                            @if($orderSubProduct->order_product_id == $order->order_productsid)
                               @php
                                   $k = $k+1
                               @endphp
                            @endif
                        @endforeach
                        <div class="col-sm-2">
                            <a id="insert_sub_product" style="margin-bottom: 20px;color: white;cursor: pointer" class="insert_sub_product btn btn-xs btn-primary"><i class="fas fa-fw fa-plus-circle"></i></a>
                            <a id="minus_sub_product" style="margin-bottom: 20px;color: white;cursor: pointer;{{$k == 0 ? 'display:none;' : ''}}" class="minus_sub_product btn btn-xs btn-danger" value="{{$k}}"><i class="fas fa-fw fa-minus-circle"></i></a>
                        </div>
                    </div>
                </div>

                @php
                    $y = count($orderSubProducts);
                @endphp

                @foreach($orderSubProducts as $orderSubProduct)
                    @if($orderSubProduct->order_product_id == $order->order_productsid)
                        @php
                            $temp = $i;
                        @endphp
                        <div class="form-group row">
                            <div class="col-sm-2 mb-3 mb-sm-0"></div>
                            <div class="col-sm-5 mb-3 mb-sm-0">
                                <select id="sub-category-select" class="sub-category-select select selectpicker show-tick form-control" data-size="5" data-style="droplist-style" data-live-search="true" data-show-subtext="true">
                                    <option value="" selected disabled hidden>{{__('category.select.option')}}</option>
                                    @foreach($categories as $category)
                                        @if($category->id == $orderSubProduct->sub_product_category_id)
                                            <option category_id="{{$category->id}}" value="{{$category->id}}" selected>{{$category->name}}</option>
                                        @else
                                            <option category_id="{{$category->id}}" value="{{$category->id}}">{{$category->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-5 mb-3 mb-sm-0">
                                <select id="sub-product-select" class="sub-product-select select selectpicker show-tick form-control" data-size="5" data-style="droplist-style" data-live-search="true" data-show-subtext="true">
                                    <option value="" selected disabled hidden>{{__('product.select.option')}}</option>
                                    @foreach($products as $product)
                                        @if($product->id == $orderSubProduct->sub_product_id)
                                            <option product_id="{{$product->id}}" product_name="{{$product->name}}" product_model_no="{{$product->model_no}}" product_price="{{$product->price}}" value="{{$product->id}}" data-subtext="{{$product->category_name}}" selected>{{$product->name}}</option>
                                        @elseif($product->category_id == $orderSubProduct->sub_product_category_id)
                                            <option product_id="{{$product->id}}" product_name="{{$product->name}}" product_model_no="{{$product->model_no}}" product_price="{{$product->price}}" value="{{$product->id}}" data-subtext="{{$product->category_name}}">{{$product->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="add_sub_product{{$y}}">
                            <div class="form-group row">
                                <div class="col-2 col-sm-2 mb-3 mb-sm-0"></div>
                                <div class="col-4 col-sm-4 mb-3 mb-sm-0" style="display: none">
                                    <input type="text" class="input-material form-control" id="sub_product_id" name="sub_product_id[]" value="{{$orderSubProduct->sub_product_id}}">
                                    <!--sub_product_id must be first one-->
                                    <input type="text" class="input-material form-control" id="ui_sub_product_id" name="ui_sub_product_id[]" value="{{$i}}">
                                </div>
                                <div class="col-4 col-sm-4 mb-3 mb-sm-0">
                                    <input type="text" class="input-material form-control" id="sub_product_name{{$y}}" name="sub_product_name[]" placeholder="Enter Product Name" value="{{$orderSubProduct->sub_product_name}}" required>
                                    <label for="sub_product_name{{$y}}" class="input-label">{{__('product.sub.name')}}</label>
                                    <input type="hidden" id="sub_product_db_id" name="sub_product_db_id[]" value="{{$orderSubProduct->id}}">
                                </div>
                                <div class="col-3 col-sm-3 mb-3 mb-sm-0">
                                    <input type="text" class="input-material form-control" id="sub_product_model_no{{$y}}" name="sub_product_model_no[]" placeholder="Enter Model Number" value="{{$orderSubProduct->sub_product_model_no}}">
                                    <label for="sub_product_model_no{{$y}}" class="input-label">{{__('product.sub.model.name')}}</label>
                                </div>
                                <div class="col-3 col-sm-3">
                                    <input type="text" class="input-material form-control product-price" id="sub_product_price{{$y}}" name="sub_product_price[]" placeholder="Enter Price" value="{{$orderSubProduct->sub_product_price}}" required>
                                    <label for="sub_product_price{{$y}}" class="input-label">{{__('product.sub.price')}}</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-2 col-sm-2 mb-3 mb-sm-0">
                                </div>
                                <div class="col-5 col-sm-5 mb-3 mb-sm-0">
                                    <input type="text" class="input-material form-control product-price" min="1" id="sub_product_quantity{{$y}}" name="sub_product_quantity[]" placeholder="Enter Quantity Name" value="{{$orderSubProduct->sub_product_quantity}}" required>
                                    <label for="sub_product_quantity{{$y}}" class="input-label">{{__('product.quantity')}}</label>
                                </div>
                                <div class="col-5 col-sm-5 mb-3 mb-sm-0">
                                    <input type="text" class="input-material form-control total-price" id="sub_product_total_price{{$y}}" name="sub_product_total_price[]" placeholder="Enter Total Price" value="{{$orderSubProduct->sub_product_total_price}}">
                                    <label for="sub_product_total_price{{$y}}" class="input-label">{{__('product.sub.total.price')}}</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-2 col-sm-2 mb-3 mb-sm-0">
                                </div>
                                <div class="col-5 col-sm-5 mb-3 mb-sm-0">
                                    <input type="text" class="input-material form-control" id="sub_product_serial_no{{$y}}" name="sub_product_serial_no[]" placeholder="Enter Product Serial Number" value="{{$orderSubProduct->sub_product_serial_no}}">
                                    <label for="sub_product_serial_no{{$y}}" class="input-label">{{__('product.sub.serial.number')}}</label>
                                </div>
                                <div class="col-5 col-sm-5 mb-3 mb-sm-0">
                                    <input type="text" class="input-material form-control" id="sub_product_remark{{$y}}" name="sub_product_remark[]" placeholder="Enter Product Remark" value="{{$orderSubProduct->sub_product_remark}}">
                                    <label for="sub_product_remark{{$y}}" class="input-label">{{__('product.sub.remark')}}</label>
                                </div>
                            </div>
                        </div>
                    @endif
                    @php
                        $y--;
                    @endphp

                @endforeach
                @php
                    $i++;
                @endphp
            @endforeach

            <hr/>
            <a class="rounded" style="display: inline;right: 4rem;bottom: 1rem;width: 15rem;position: fixed;height: 2.75rem;text-align: center;color: #fff;line-height: 46px;">
                <label class="input-material form-control" id="sum_total_price" style="color: red;">$ {{$orders[0]->total_price}}</label>
                <input type="hidden" class="input-material form-control" id="order_total_price" name="order_total_price" value="{{$orders[0]->total_price}}">
            </a>
            <button type="submit" class="btn btn-primary btn-block">
                {{Route::current()->getName() == "order.edit"?"Edit":"Create Order From Template"}}
            </button>
        </form>
        <div id="add_new_product" class="add_product non-display">
            <div class="form-group row">
                <div class="col-sm-4 mb-3 mb-sm-0" style="display: none">
                    <input type="text" class="input-material form-control" id="product_id" name="product_id[]" value="0">
                    <!--product_id must be first one-->
                    <input type="text" class="input-material form-control" id="ui_product_id" name="ui_product_id[]" value="0">

                </div>
                <div class="col-sm-4 mb-3 mb-sm-0">
                    <input type="text" class="input-material form-control" id="product_name" name="product_name[]" placeholder="Enter Product Name" value="" required="">
                    <label for="product_name" class="input-label">{{__('product.name')}}</label>
                    <input type="hidden" id="order_productsid" name="order_productsid[]" value="0">
                </div>
                <div class="col-sm-4 mb-3 mb-sm-0">
                    <input type="text" class="input-material form-control" id="product_model_no" name="product_model_no[]" placeholder="Enter Model Number" value="">
                    <label for="product_model_no" class="input-label">{{__('product.model.name')}}</label>
                </div>
                <div class="col-sm-4">
                    <input type="text" class="input-material form-control product-price" id="product_price" name="product_price[]" placeholder="Enter Price" value="0" required="">
                    <label for="product_price" class="input-label">{{__('product.price')}}</label>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="input-material form-control product-price" min="1" id="product_quantity" name="product_quantity[]" placeholder="Enter Quantity Name" value="1" required="">
                    <label for="quantity" class="input-label">{{__('product.quantity')}}</label>
                </div>
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="input-material form-control total-price" id="product_total_price" name="product_total_price[]" placeholder="Enter Total Price" value="0">
                    <label for="product_total_price" class="input-label">{{__('product.total.price')}}</label>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-5 mb-3 mb-sm-0">
                    <input type="text" class="input-material form-control" id="product_serial_no" name="product_serial_no[]" placeholder="Enter Product Serial Number" value="">
                    <label for="product_serial_no" class="input-label">{{__('product.serial.number')}}</label>
                </div>
                <div class="col-sm-5 mb-3 mb-sm-0">
                    <input type="text" class="input-material form-control" id="product_remark" name="product_remark[]" placeholder="Enter Product Remark" value="">
                    <label for="product_remark" class="input-label">{{__('product.remark')}}</label>
                </div>
                <div class="col-sm-2">
                    <a id="insert_sub_product" style="margin-bottom: 20px;color: white;cursor: pointer" class="insert_sub_product btn btn-xs btn-primary"><i class="fas fa-fw fa-plus-circle"></i></a>
                    <a id="minus_sub_product" style="margin-bottom: 20px;color: white;cursor: pointer;display:none" class="minus_sub_product btn btn-xs btn-danger" value="0"><i class="fas fa-fw fa-minus-circle"></i></a>
                </div>
            </div>
        </div>
        <div id="add_sub_product" class="non-display">
            <div class="form-group row">
                <div class="col-2 col-sm-2 mb-3 mb-sm-0">
                </div>
                <div class="col-4 col-sm-4 mb-3 mb-sm-0" style="display: none">
                    <input type="text" class="input-material form-control" id="sub_product_id" name="sub_product_id[]">
                    <!--sub_product_id must be first one-->
                    <input type="text" class="input-material form-control" id="ui_sub_product_id" name="ui_sub_product_id[]">
                </div>
                <div class="col-4 col-sm-4 mb-3 mb-sm-0">
                    <input type="text" class="input-material form-control" id="sub_product_name" name="sub_product_name[]" placeholder="Enter Product Name" required>
                    <label for="sub_product_name" class="input-label">{{__('product.sub.name')}}</label>
                    <input type="hidden" id="sub_product_db_id" name="sub_product_db_id[]" value="">
                </div>
                <div class="col-3 col-sm-3 mb-3 mb-sm-0">
                    <input type="text" class="input-material form-control" id="sub_product_model_no" name="sub_product_model_no[]" placeholder="Enter Model Number">
                    <label for="sub_product_model_no" class="input-label">{{__('product.sub.model.name')}}</label>
                </div>
                <div class="col-3 col-sm-3">
                    <input type="text" class="input-material form-control product-price" id="sub_product_price" name="sub_product_price[]" placeholder="Enter Price" required>
                    <label for="sub_product_price" class="input-label">{{__('product.sub.price')}}</label>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-2 col-sm-2 mb-3 mb-sm-0">
                </div>
                <div class="col-5 col-sm-5 mb-3 mb-sm-0">
                    <input type="text" class="input-material form-control product-price" min="1" id="sub_product_quantity" name="sub_product_quantity[]" placeholder="Enter Quantity Name" value="1" required>
                    <label for="sub_product_quantity" class="input-label">{{__('product.quantity')}}</label>
                </div>
                <div class="col-5 col-sm-5 mb-3 mb-sm-0">
                    <input type="text" class="input-material form-control total-price" id="sub_product_total_price" name="sub_product_total_price[]" placeholder="Enter Total Price">
                    <label for="sub_product_total_price" class="input-label">{{__('product.sub.total.price')}}</label>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-2 col-sm-2 mb-3 mb-sm-0">
                </div>
                <div class="col-5 col-sm-5 mb-3 mb-sm-0">
                    <input type="text" class="input-material form-control" id="sub_product_serial_no" name="sub_product_serial_no[]" placeholder="Enter Product Serial Number">
                    <label for="sub_product_serial_no" class="input-label">{{__('product.sub.serial.number')}}</label>
                </div>
                <div class="col-5 col-sm-5 mb-3 mb-sm-0">
                    <input type="text" class="input-material form-control" id="sub_product_remark" name="sub_product_remark[]" placeholder="Enter Product Remark">
                    <label for="sub_product_remark" class="input-label">{{__('product.sub.remark')}}</label>
                </div>
            </div>
        </div>
    </div>
@endsection