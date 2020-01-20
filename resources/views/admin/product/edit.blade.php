@extends('layouts.content')

@section('css')
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet">
    <link href="{{ asset('vendor/datepicker/datepicker.min.css')}}" rel="stylesheet">
<style>
    @media only screen and (min-width: 575px)  {
        .separate{
            position: absolute;
            bottom: 0;
            right: 0;
        }
    }

    table.dataTable tbody>tr.selected,
    table.dataTable tbody>tr>.selected {
        background-color: #A2D3F6;
    }

    div.datepicker-container { z-index: 1100 !important; }

    .form-control[readonly]{
        background-color:rgba(0, 0, 0, 0);
    }

    .readonly-label{
        border-bottom:0px
    }

    .error {
        border-color: red !important;
    }
</style>
@stop

@section('js')
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js')}}" defer></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js')}}" defer></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js" defer></script>
    <script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js" defer></script>
    <script src="{{ asset('vendor/datatables/dataTables.editor.js')}}" defer></script>
    <script src="{{ asset('vendor/datepicker/datepicker.min.js')}}" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
    <script src="{{ asset('js/order.js')}}"></script>

    <script>
        var editor; // use a global for the submit and return data rendering in the examples

        $(document).ready(function(){

            var pathArray = window.location.pathname.split('/');
            var id = pathArray[pathArray.length-1];
            var dataSet;

            dataSet = getDetails(id);
            getProductDetails(id);

            var columnDefs = [{
                title: "Product Id",
                id: "product_id"
            },{
                title: "Purchase Date",
                id: "purchase_date"
            }, {
                title: "Company Name",
                id: "company_name"
            }, {
                title: "Price",
                id: "purchase_price"
            }, {
                title: "Quantity",
                id: "purchase_quantity"
            }];

            $('#dataTable').DataTable({
                "searching": false,
                "order": [[ 1, "desc" ]],
                "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
                data: dataSet,
                columns: columnDefs,
                dom: 'Bfrtip',        // Needs button container
                select: 'single',
                responsive: true,
                altEditor: true,     // Enable altEditor
                buttons: [
                    {
                        text: 'Add',
                        name: 'add'        // do not change name
                    },
                    {
                        extend: 'selected', // Bind to Selected row
                        text: 'Edit',
                        name: 'edit'        // do not change name
                    },
                    {
                        extend: 'selected', // Bind to Selected row
                        text: 'Delete',
                        name: 'delete'      // do not change name
                    }]
            });
        });

        function getDetails(id){
            var data;
            $.ajax({
                url: "/admin/product/getDetails",
                type:"get",
                async: false,
                data:{
                    id:id,
                },
                success: function(result){
                    data = result['data'];
                }
            });
            return data;
        }
        function getProductDetails(id){
            $.ajax({
                url: "/admin/product/getProductDetails",
                type:"get",
                data:{
                    id:id,
                },
                success: function(result){
                    $("#total_purchase_quantity").val(0);
                    $("#total_purchase_price").val(0);
                    $("#average_price").val(0);
                    $("#remaining_quantity").val(0);

                    if(result['data'][0]['purchase_quantity'] != null){
                        $("#total_purchase_quantity").val(result['data'][0]['purchase_quantity']);
                    }
                    if(result['data'][0]['purchase_price'] != null){
                        $("#total_purchase_price").val(currencyFormat(result['data'][0]['purchase_price'],'$',2));
                    }
                    if(result['data'][0]['average'] != null){
                        $("#average_price").val(currencyFormat(result['data'][0]['average'],'$',3));
                    }
                    if(result['data'][1]['product_quantity'] != null){
                        $("#remaining_quantity").val(result['data'][0]['purchase_quantity']-result['data'][1]['product_quantity']);
                    }
                }
            });
        }
    </script>
@stop

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">{{__('product.edit.title')}}</h1>
        <p class="mb-4"></p>

        <form method="POST" action="{{route('product.update')}}">
            @csrf
            <div class="form-group row">
                <input type="hidden" class="form-control" name="id" value="{{$product->id}}">
                <div class="col-sm-12 mb-3 mb-sm-0">
                    <select class="selectpicker show-tick form-control" data-size="5" data-style="droplist-style" data-live-search="true" title="Choose one of the category..." name="category_id">
                        @foreach($categories as $category)
                            @if($product->category_id == $category->id)
                                <option value="{{$category->id}}" selected>{{$category->name}}</option>
                            @else
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-5 mb-3 mb-sm-0">
                    <input type="text" class="input-material form-control" name="name" id="name" placeholder="Enter Product Name" value="{{$product->name}}" required>
                    <label for="name" class="input-label">{{__('product.title')}}</label>
                </div>
                <div class="col-sm-5">
                    <input type="number" class="input-material form-control" id="price" name="price" placeholder="Enter Price" value="{{$product->price}}" required>
                    <label for="price" class="input-label">{{__('product.price')}}</label>
                </div>
                <div class="col-sm-2 mb-2 mb-sm-0 separate">
                    <div class="custom-control custom-checkbox">
                        @if(is_null($product->sale_check))
                            <input type="checkbox" class="custom-control-input" id="sale_check" name="sale_check" value="Y">
                        @else
                            <input type="checkbox" class="custom-control-input" id="sale_check" name="sale_check" value="{{$product->sale_check}}" checked>
                        @endif
                        <label class="custom-control-label" for="sale_check">{{__('product.sale.check')}}</label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-5 mb-3 mb-sm-0">
                    <input type="text" class="input-material form-control" id="model_no" name="model_no" placeholder="Enter Model Number" value="{{$product->model_no}}">
                    <label for="model_no" class="input-label">{{__('product.model.name')}}</label>
                </div>
                <div class="col-sm-5 mb-3 mb-sm-0">
                    <input type="number" class="input-material form-control" id="maintenance_period" name="maintenance_period" placeholder="Enter Maintenance Period (Month)" value="{{$product->maintenance_period}}">
                    <label for="maintenance_period" class="input-label">{{__('product.maintenance.period')}}</label>
                </div>
                <div class="col-sm-2 mb-2 mb-sm-0 separate">
                    <div class="custom-control custom-checkbox">
                        @if(is_null($product->device_check))
                            <input type="checkbox" class="custom-control-input" id="device_check" name="device_check" value="Y">
                        @else
                            <input type="checkbox" class="custom-control-input" id="device_check" name="device_check" value="{{$product->device_check}}" checked>
                        @endif
                        <label class="custom-control-label" for="device_check">{{__('product.maintenance.period')}} </label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">
                {{__('product.edit')}}
            </button>
        </form>
        </br>
        @can('hasInventoryAccess',Auth::user())
            <div class="row">
                <div class="col-2 col-sm-12 col-md-2 col-lg-2">
                    <div class="row">
                        <div class="col-12" style="margin-bottom: 20px;">
                            <input type="text" class="input-material form-control readonly-label" id="total_purchase_price" value="0" readonly>
                            <label class="input-label">{{__('product.total.purchase.price')}}</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12" style="margin-bottom: 20px;">
                            <input type="text" class="input-material form-control readonly-label" id="total_purchase_quantity" value="0" readonly>
                            <label class="input-label">{{__('product.total.purchase.quantity')}}</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12" style="margin-bottom: 20px;">
                            <input type="text" class="input-material form-control readonly-label" id="average_price" value="0" readonly>
                            <label class="input-label">{{__('product.average.price')}}</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12" style="margin-bottom: 20px;">
                            <input type="text" class="input-material form-control readonly-label" id="remaining_quantity" value="0" readonly>
                            <label class="input-label">{{__('product.remaining.quantity')}}</label>
                        </div>
                    </div>
                </div>
                <div class="col-10 col-sm-12 col-md-10 col-lg-10">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable"></table>
                    </div>
                </div>
            </div>
        @endcan
    </div>
@endsection
