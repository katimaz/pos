<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Search -->
    {{--<form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">--}}
        {{--<div class="input-group">--}}
            {{--<input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">--}}
            {{--<div class="input-group-append">--}}
                {{--<button class="btn btn-primary" type="button">--}}
                    {{--<i class="fas fa-search fa-sm"></i>--}}
                {{--</button>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</form>--}}

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            @php $locale = session()->get('locale'); @endphp
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    @switch($locale)
                        @case('cn')
                        <img src="{{asset('img/lang/tw.png')}}" width="22px" height="20px">
                        @break
                        @default
                        <img src="{{asset('img/lang/en.png')}}" width="22px" height="20px">
                    @endswitch
                    <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{'/en'}}"><img src="{{asset('img/lang/en.png')}}" width="18px" height="12px"> English</a>
                    <a class="dropdown-item" href="{{'/cn'}}"><img src="{{asset('img/lang/tw.png')}}" width="18px" height="12px"> 中文</a>
                </div>
            </li>
        </ul>
        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{isset(Auth::user()->name)?strtoupper(Auth::user()->name):''}}</span>
                <img class="img-profile rounded-circle" src="{{url('img/profile.jpg')}}">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('logout') }}" data-toggle="modal" data-target="#logoutModal" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
                </form>

            </div>
        </li>
    </ul>
</nav>
<!-- End of Topbar -->