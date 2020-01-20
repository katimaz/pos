@extends('layouts.content')

@section('css')
    <style>
        .chart-select{
            display: initial;
            width: auto;
        }
    </style>
@stop

@section('js')
    <script>

    </script>
    <!-- Page level plugins -->
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
    <!-- Page level custom scripts -->
    <script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('js/demo/chart-month.js') }}"></script>
    {{--<script src="{{ asset('js/demo/chart-pie-demo.js') }}" defer></script>--}}

    <script>
        $(document).ready(function() {

            function updateChart(){
                $.ajax({
                    async: false,
                    type: 'GET',
                    data: { year: $( "#chart_year" ).val(), month:$( "#chart_month" ).val()} ,
                    url: '/admin/getdata',
                    success: function(result) {
                        var data = myLineChart.config.data;
                        data.labels = result[1];
                        data.datasets[0].data = result[0];
                        myLineChart.update();
                    }
                });
            }
            $( "#chart_month" ).change(function() {
                updateChart();
            });
            $( "#chart_year" ).change(function() {
                updateChart();
            });
            $( "#chart_monthly_year" ).change(function() {
                $.ajax({
                    async: false,
                    type: 'GET',
                    data: { year: $( "#chart_monthly_year" ).val()} ,
                    url: '/admin/getmonthdata',
                    success: function(result) {
                        var data = myMonthChart.config.data;
                        data.datasets[0].data = Object.values(result);
                        myMonthChart.update();
                    }
                });
            });
            $("#1").click(function() {
                var chart_labels = ['00:00', '03:00', '06:00', '09:00', '12:00', '15:00', '18:00', '21:00'];
                var temp_dataset = ['5', '3', '4', '8', '10', '11', '10', '9'];
                var rain_dataset = ['0', '0', '1', '4', '19', '19', '7', '2'];
                var data = myMonthChart.config.data;
                data.datasets[0].data = temp_dataset;
                data.labels = chart_labels;
                myMonthChart.update();
            });
        });
    </script>
@stop

@section('content')

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">{{__('dashboard.stat')}}</h1>
                        {{--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>--}}
                    </div>

                    <!-- Content Row -->
                    @can('hasDashboardAccess',Auth::user())
                    <div class="row">

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{__('dashboard.earning.today')}}</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{number_format($total_day_revenue,2)}}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{__('dashboard.earning.last.day')}}</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{number_format($total_week_revenue,2)}}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">{{__('dashboard.earning')}} ( {{__('dashboard.'.Carbon\Carbon::now()->format('F'))}} )</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{number_format($total_month_revenue,2)}}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{__('dashboard.earning')}}</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{number_format($total_revenue,2)}}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{__('dashboard.earning.average')}}</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{number_format($average_revenue,2)}}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{__('order.title')}}</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$order_count}}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-receipt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">{{__('product.title')}}</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$product_count}}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-industry fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{__('customer.title')}}</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$customer_count}}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">{{__('dashboard.earning.overview')}}</h6>
                                    <div>
                                        <select id="chart_year" class="form-control chart-select">
                                            <option value="2019" {{Carbon\Carbon::now()->format('Y') == '2019' ? 'selected':''}}>2019</option>
                                            <option value="2020" {{Carbon\Carbon::now()->format('Y') == '2020' ? 'selected':''}}>2020</option>
                                            <option value="2021" {{Carbon\Carbon::now()->format('Y') == '2021' ? 'selected':''}}>2021</option>
                                            <option value="2022" {{Carbon\Carbon::now()->format('Y') == '2022' ? 'selected':''}}>2022</option>
                                            <option value="2023" {{Carbon\Carbon::now()->format('Y') == '2023' ? 'selected':''}}>2023</option>
                                            <option value="2024" {{Carbon\Carbon::now()->format('Y') == '2024' ? 'selected':''}}>2024</option>
                                            <option value="2025" {{Carbon\Carbon::now()->format('Y') == '2025' ? 'selected':''}}>2025</option>
                                        </select>
                                        <select id="chart_month" class="form-control chart-select">
                                            <option value="Jan" {{Carbon\Carbon::now()->format('M') == 'Jan' ? 'selected':''}}>{{__('dashboard.jan')}}</option>
                                            <option value="Feb" {{Carbon\Carbon::now()->format('M') == 'Feb' ? 'selected':''}}>{{__('dashboard.feb')}}</option>
                                            <option value="Mar" {{Carbon\Carbon::now()->format('M') == 'Mar' ? 'selected':''}}>{{__('dashboard.mar')}}</option>
                                            <option value="Apr" {{Carbon\Carbon::now()->format('M') == 'Apr' ? 'selected':''}}>{{__('dashboard.apr')}}</option>
                                            <option value="May" {{Carbon\Carbon::now()->format('M') == 'May' ? 'selected':''}}>{{__('dashboard.may')}}</option>
                                            <option value="Jun" {{Carbon\Carbon::now()->format('M') == 'Jun' ? 'selected':''}}>{{__('dashboard.jun')}}</option>
                                            <option value="Jul" {{Carbon\Carbon::now()->format('M') == 'Jul' ? 'selected':''}}>{{__('dashboard.jul')}}</option>
                                            <option value="Aug" {{Carbon\Carbon::now()->format('M') == 'Aug' ? 'selected':''}}>{{__('dashboard.aug')}}</option>
                                            <option value="Sep" {{Carbon\Carbon::now()->format('M') == 'Sep' ? 'selected':''}}>{{__('dashboard.sep')}}</option>
                                            <option value="Oct" {{Carbon\Carbon::now()->format('M') == 'Oct' ? 'selected':''}}>{{__('dashboard.oct')}}</option>
                                            <option value="Nov" {{Carbon\Carbon::now()->format('M') == 'Nov' ? 'selected':''}}>{{__('dashboard.nov')}}</option>
                                            <option value="Dec" {{Carbon\Carbon::now()->format('M') == 'Dec' ? 'selected':''}}>{{__('dashboard.dec')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-success">{{__('dashboard.earning.overview.monthly')}}</h6>
                                    <div>
                                        <select id="chart_monthly_year" class="form-control chart-select">
                                            <option value="2019" {{Carbon\Carbon::now()->format('Y') == '2019' ? 'selected':''}}>2019</option>
                                            <option value="2020" {{Carbon\Carbon::now()->format('Y') == '2020' ? 'selected':''}}>2020</option>
                                            <option value="2021" {{Carbon\Carbon::now()->format('Y') == '2021' ? 'selected':''}}>2021</option>
                                            <option value="2022" {{Carbon\Carbon::now()->format('Y') == '2022' ? 'selected':''}}>2022</option>
                                            <option value="2023" {{Carbon\Carbon::now()->format('Y') == '2023' ? 'selected':''}}>2023</option>
                                            <option value="2024" {{Carbon\Carbon::now()->format('Y') == '2024' ? 'selected':''}}>2024</option>
                                            <option value="2025" {{Carbon\Carbon::now()->format('Y') == '2025' ? 'selected':''}}>2025</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myMonthAreaChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan
                <!-- /.container-fluid -->

        <!-- End of Content Wrapper -->


    <!-- End of Page Wrapper -->



@endsection
