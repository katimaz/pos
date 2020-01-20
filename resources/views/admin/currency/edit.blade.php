@extends('layouts.content')

@section('js')

@stop

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">{{__('currency.edit.title')}}</h1>
        <p class="mb-4"></p>
        <form method="POST" action="{{route('currency.update')}}">
            @csrf
            <div class="form-group row">
                <input type="hidden" class="form-control" name="id" value="{{$currency->id}}">
                <div class="col-sm-4 mb-3 mb-sm-0">
                    <input type="text" class="input-material form-control" name="name" id="name" placeholder="Enter Currency Name" value="{{$currency->name}}" required>
                    <label for="name" class="input-label">{{__('currency.name')}}</label>
                </div>
                <div class="col-sm-4 mb-3 mb-sm-0">
                    <input type="text" class="input-material form-control" name="en_name" id="en_name" placeholder="Enter Currency English Name" value="{{$currency->en_name}}" required>
                    <label for="en_name" class="input-label">{{__('currency.english.name')}}</label>
                </div>
                <div class="col-sm-4">
                    <input type="text" class="input-material form-control" id="ratio" name="ratio" placeholder="Enter Ratio" value="{{$currency->ratio}}" required>
                    <label for="ratio" class="input-label">{{__('currency.ratio')}}</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">
                {{__('currency.edit')}}
            </button>
        </form>
    </div>
@endsection
