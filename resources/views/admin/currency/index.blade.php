@extends('layouts.content')

@section('css')
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
                    targets: [ 6 ]
                }]
            });

            $('.delete').on('click', function(e) {
               $('#currency_id').val(this.id);
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
                            <h6 class="m-0 font-weight-bold text-primary">{{__('currency.title')}}</h6>
                        </div>
                        <div class="card-body">
                            <a style="margin-bottom: 20px" href="{{route('currency.add')}}" class="btn btn-xs btn-success"><i class="fas fa-fw fa-plus-circle"></i></a>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{__('currency.name')}}</th>
                                        <th>{{__('currency.english.name')}}</th>
                                        <th>{{__('currency.ratio')}}</th>
                                        <th>{{__('currency.updated.by')}}</th>
                                        <th>{{__('currency.updated.time')}}</th>
                                        <th>{{__('currency.action')}}</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>{{__('currency.name')}}</th>
                                        <th>{{__('currency.english.name')}}</th>
                                        <th>{{__('currency.ratio')}}</th>
                                        <th>{{__('currency.updated.by')}}</th>
                                        <th>{{__('currency.updated.time')}}</th>
                                        <th>{{__('currency.action')}}</th>
                                    </tr>
                                    </tfoot>
                                    <tbody>

                                    @foreach($currencies as $currency)
                                        <tr>
                                            <td>{{$currency->id}}</td>
                                            <td>{{$currency->name}}</td>
                                            <td>{{$currency->en_name}}</td>
                                            <td>{{$currency->ratio}}</td>
                                            <td>{{$currency->updated_by}}</td>
                                            <td>{{$currency->updated_at}}</td>
                                            <td>
                                                <a style="margin: 2px" href="{{route('currency.edit', [$currency->id])}}" class="btn btn-xs btn-primary"><i class="fas fa-fw fa-edit"></i></a>
                                                <a style="margin: 2px" href id="{{$currency->id}}" data-toggle="modal" data-target="#destoryModal" class="btn btn-xs btn-danger delete"><i class="fas fa-fw fa-trash"></i></a>
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
                                <h5 class="modal-title" id="exampleModalLabel">{{__('currency.delete.title')}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                {{__('currency.delete.description')}}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('currency.close')}}</button>
                                <form method="post" action="{{route('currency.destroy')}}" class="inline">
                                    @csrf
                                    <input type="hidden" id="currency_id" name="id">
                                    <button type="submit" class="btn btn-danger">{{__('currency.yes')}}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
@endsection
