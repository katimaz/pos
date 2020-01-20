@extends('layouts.app')

@section('js')
    <script>
    $(".alert").fadeTo(2500, 500).slideUp(500, function(){
        $(".alert").slideUp(500);
    });
    </script>
@stop

@section('contents')
    <div id="wrapper">
    @auth
        @include('layouts.sidebar')
    @endauth
    <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                @auth
                    @include('layouts.topbar')
                @endauth

                @if(Session::has('success'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <strong>Success!</strong> {{ Session::get('message', '') }}
                    </div>
                @endif

                @yield('content')
                <!-- Scroll to Top Button-->
                <a class="scroll-to-top rounded" href="#page-top">
                    <i class="fas fa-angle-up"></i>
                </a>
            </div>

            <!-- End of Main Content -->
            @include('layouts.footer')
        </div>
    </div>
@endsection



