@extends('layouts.content')

@section('js')

@stop

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">{{__('user.create')}}</h1>
        <p class="mb-4"></p>

        <form class="user" method="POST" action="{{route('user.create')}}">
            @csrf
            <div class="form-group row">
                <div class="col-sm-4 mb-3 mb-sm-0">
                    <input type="text" class="input-material form-control" name="name" id="name" placeholder="Enter Name" required>
                    <label for="name" class="input-label">{{__('user.name')}}</label>
                </div>
                <div class="col-sm-4">
                    <input type="text" class="input-material form-control" id="email" name="email" placeholder="Enter Email" required>
                    <label for="code" class="input-label">{{__('user.email')}}</label>
                </div>
                <div class="col-sm-4">
                    <input type="password" class="input-material form-control" id="password" name="password" placeholder="Enter password" required>
                    <label for="password" class="input-label">{{__('user.password')}}</label>
                </div>
            </div>

            <h1 class="h3 mb-2 text-gray-800">{{__('role.title')}}</h1>
            @foreach($roles as $key => $role)
                @if($key % 6 == 0)
                    <div class="form-group row">
                        @endif
                        <div class="col-sm-2 mb-3 mb-sm-0">
                            <div class="custom-control custom-checkbox small">
                                <input type="checkbox" class="custom-control-input" name="roles[]" value="{{$role->id}}" id="{{$role->name}}" {{$role->role_id != null?'checked' : ''}}>
                                <label class="custom-control-label" for="{{$role->name}}">{{$role->description}}</label>
                            </div>
                        </div>
                        @if( ($key+1) % 6 == 0)
                    </div>
                @endif
                @if(count($roles) == ($key+1))
                </div>
                @endif
            @endforeach
            <button type="submit" class="btn btn-primary btn-block">
                {{__('user.add')}}
            </button>
        </form>
    </div>
@endsection
