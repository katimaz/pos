@extends('layouts.content')

@section('css')
    @parent
    <link href="{{ asset('vendor/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet">
    <style>
        .droplist-style{
            color: #6e707e !important;
            background-color: #fff !important;
            background-clip: padding-box !important;
            border: 1px solid #d1d3e2 !important;
            border-radius: 0.35rem !important;
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
    <script src="{{ asset('vendor/bootstrap-select/bootstrap-select.min.js')}}" defer></script>
@stop

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">{{__('product.create.title')}}</h1>
        <p class="mb-4"></p>

        <form method="POST" action="{{route('product.create')}}">
            @csrf
            <div class="form-group row">
                <div class="col-sm-12 mb-3 mb-sm-0">
                    <select class="selectpicker show-tick form-control" data-size="5" data-style="droplist-style" data-live-search="true" title="{{__('product.select.option')}}" name="category_id" required>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-5 mb-3 mb-sm-0">
                    <input type="text" class="input-material form-control" name="name" id="name" placeholder="Enter Product Name" required>
                    <label for="name" class="input-label">{{__('product.name')}}</label>
                </div>
                <div class="col-sm-5  mb-3 mb-sm-0">
                    <input type="number" class="input-material form-control" id="price" name="price" placeholder="Enter Price" required>
                    <label for="price" class="input-label">{{__('product.price')}}</label>
                </div>
                <div class="col-sm-2 mb-2 mb-sm-0 separate">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="sale_check" name="sale_check" value="Y">
                        <label class="custom-control-label" for="sale_check">{{__('product.sale.check')}}</label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-5 mb-3 mb-sm-0">
                    <input type="text" class="input-material form-control" id="model_no" name="model_no" placeholder="Enter Model Number">
                    <label for="model_no" class="input-label">{{__('product.model.name')}}</label>
                </div>
                <div class="col-sm-5 mb-3 mb-sm-0">
                    <input type="number" class="input-material form-control" id="maintenance_period" name="maintenance_period" placeholder="Enter Maintenance Period (Month)">
                    <label for="maintenance_period" class="input-label">{{__('product.maintenance.period')}}</label>
                </div>
                <div class="col-sm-2 mb-2 mb-sm-0 separate">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="device_check" name="device_check" value="Y">
                        <label class="custom-control-label" for="device_check">{{__('product.device.check')}}</label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">
                {{__('product.add')}}
            </button>
        </form>
    </div>
@endsection
