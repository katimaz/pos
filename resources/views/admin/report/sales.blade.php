@extends('layouts.content')

@section('css')
    @parent
    <link href="{{ asset('vendor/datepicker/datepicker.min.css')}}" rel="stylesheet">
@stop

@section('js')
    <script src="{{ asset('vendor/datepicker/datepicker.min.js')}}"></script>
    <script src="{{ asset('js/report.js')}}"></script>
@stop

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">{{__('reports.sale.title')}}</h1>
        <p class="mb-4"></p>

        <form method="get" action="{{route('exportSales')}}">
            @csrf
            <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <input id="from_date" name="from_date" class="input-material form-control" autocomplete="off">
                    <label for="from_date" class="input-label">{{__('reports.from.date')}}</label>
                </div>
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <input id="to_date" name="to_date" class="input-material form-control" autocomplete="off">
                    <label for="to_date" class="input-label">{{__('reports.to.date')}}</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">
                {{__('reports.generate')}}
            </button>
        </form>
    </div>
@endsection
