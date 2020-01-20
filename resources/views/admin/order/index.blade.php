@extends('layouts.content')

@section('css')
    @if(Session::has('exportExcel'))
        <meta http-equiv="refresh" content="1;url={{ Session::get('exportExcel') }}">
    @endif
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@stop

@section('js')
    @parent
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js')}}" defer></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js')}}" defer></script>

    <script>
        $(document).ready(function(){
            var locale = "{{ Session::get('locale')}}"
            if(locale == 'cn'){
                $langUrl = '/vendor/datatables/lang/chinese.json';
            }else{
                $langUrl = '/vendor/datatables/lang/english.json';
            }

            $('#dataTable').DataTable({
                "scrollX": true,
                "language": {
                    "url": $langUrl
                },
                columnDefs: [{
                    orderable: false,
                    targets: [ 7 ]
                }],
                "order": [[ 6, "desc" ]]
            });

            $('.delete').on('click', function(e) {
               $('#order_id').val(this.id);
            });
        });
    </script>
@stop

@section('content')

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">{{__('order.title')}}</h6>
                        </div>
                        <div class="card-body">
                            <a style="margin-bottom: 20px" href="{{route('order.add')}}" class="btn btn-xs btn-success"><i class="fas fa-fw fa-plus-circle"></i></a>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{__('order.order.date')}}</th>
                                        <th>{{__('customer.name')}}</th>
                                        <th>{{__('customer.phone')}}</th>
                                        <th>{{__('order.price')}}</th>
                                        <th>{{__('order.created.by')}}</th>
                                        <th>{{__('order.created.time')}}</th>
                                        <th>{{__('order.action')}}</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Order Date</th>
                                        <th>Customer Name</th>
                                        <th>Customer Phone</th>
                                        <th>Price</th>
                                        <th>Created By</th>
                                        <th>Created Time</th>
                                        <th>Action</th>
                                    </tr>
                                    </tfoot>
                                    <tbody>

                                    @foreach($orders as $order)
                                        <tr>
                                            @if($order->order_type != "S")
                                                <td>{{$order->order_no.$order->order_type.$order->separate}}</td>
                                            @else
                                                <td>{{$order->order_no.$order->separate}}</td>
                                            @endif
                                            <td>{{$order->order_date}}</td>
                                            <td>{{$order->customer_name}}</td>
                                            <td>{{$order->customer_phone}}</td>
                                            <td>${{number_format($order->total_price,2)}}</td>
                                            <td>{{$order->created_by}}</td>
                                            <td>{{$order->created_at}}</td>
                                            <td>
                                                <a style="margin: 2px" href="{{route('export', [$order->id])}}" class="btn btn-xs btn-info"><i class="fas fa-fw fa-download"></i></a>
                                                <a style="margin: 2px" href="{{route('order.edit', [$order->id])}}" class="btn btn-xs btn-primary"><i class="fas fa-fw fa-edit"></i></a>
                                                <a style="margin: 2px" href id="{{$order->id}}" data-toggle="modal" data-target="#destoryModal" class="btn btn-xs btn-danger delete"><i class="fas fa-fw fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="destoryModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">{{__('order.delete.title')}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                {{__('order.delete.description')}}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('order.close')}}</button>
                                <form method="post" action="{{route('order.destroy')}}" class="inline">
                                    @csrf
                                    <input type="hidden" id="order_id" name="id">
                                    <button type="submit" class="btn btn-danger">{{__('order.yes')}}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
@endsection
