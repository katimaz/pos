@extends('layouts.content')

@section('js')
    <script>
        $(document).ready(function(){
            $('#same_address_check').click(function() {
                if ($(this).is(':checked')) {
                    $('#delivery_address').val($('#address').val());
                    $('#delivery_address').prop('readonly', true);
                }else{
                    $('#delivery_address').prop('readonly', false);
                }
            });
        });
    </script>
@stop

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">{{__('customer.create.title')}}</h1>
        <p class="mb-4"></p>

        <form class="user" method="POST" action="{{route('customer.create')}}">
            @csrf
            <div class="form-group row">
                <div class="col-sm-4 mb-3 mb-sm-0">
                    <select id="customer-select"class="selectpicker show-tick form-control" data-size="5" data-style="droplist-style" data-live-search="true" title="Choose one of the country..." name="country_name">
                        @foreach($countries as $country)
                            <option value="{{$country->name}}">{{$country->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-4 mb-3 mb-sm-0">
                    <input type="text" class="input-material form-control" name="name" id="name" placeholder="Enter Name" required>
                    <label for="name" class="input-label">{{__('customer.name')}}</label>
                </div>
                <div class="col-sm-4">
                    <input type="text" class="input-material form-control" id="phone" name="phone" placeholder="Enter Phone" required>
                    <label for="phone" class="input-label">{{__('customer.phone')}}</label>
                </div>
            </div>
            <div class="form-group">
                <input type="text" class="input-material form-control" id="address" name="address" placeholder="Enter Address" required>
                <label for="address" class="input-label">{{__('customer.address')}}</label>
            </div>
            <div class="form-group" style="margin-bottom: 0rem">
                <input type="text" class="input-material form-control" id="delivery_address" name="delivery_address" placeholder="Enter Delivery Address">
                <label for="delivery_address" class="input-label">{{__('customer.delivery.address')}}</label>
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox small">
                    <input type="checkbox" class="custom-control-input" id="same_address_check">
                    <label class="custom-control-label" for="same_address_check">{{__('customer.same.address')}}</label>
                </div>
            </div>
            <div class="form-group">
                <input type="text" class="input-material form-control" id="remark" name="remark" placeholder="Enter Remark">
                <label for="remark" class="input-label">{{__('customer.remark')}}</label>
            </div>
            <button type="submit" class="btn btn-primary btn-block">
                {{__('customer.add')}}
            </button>
        </form>
    </div>
@endsection
