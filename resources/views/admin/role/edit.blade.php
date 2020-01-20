@extends('layouts.content')

@section('js')

@stop

@section('css')
    @parent
    <style>
    .cursor-pointer{
        cursor: pointer;
        user-select: none;
    }
    </style>
@stop

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">{{__('role.edit.title')}}</h1>
        <p class="mb-4"></p>
        <form method="POST" action="{{route('role.update')}}">
            @csrf
            <div class="form-group row">
                <input type="hidden" class="form-control" name="id" value="{{$role->id}}">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="input-material form-control" name="name" id="name" placeholder="Enter Name" value="{{$role->name}}" required>
                    <label for="name" class="input-label">{{__('role.name')}}</label>
                </div>
                <div class="col-sm-6">
                        <input type="text" class="input-material form-control" id="description" name="description" placeholder="Enter Description" value="{{$role->description}}" required>
                    <label for="code" class="input-label">{{__('role.description')}}</label>
                </div>
            </div>
            <h1 class="h3 mb-2 text-gray-800">{{__('role.role.permission')}}</h1>

            @foreach($permissions as $key => $permission)
                @if($key % 6 == 0)
                    <div class="form-group row">
                @endif
                        <div class="col-sm-2 mb-3 mb-sm-0 cursor-pointer">
                            <div class="custom-control custom-checkbox small">
                                <input type="checkbox" class="custom-control-input" name="permissions[]" value="{{$permission->id}}" id="{{$permission->name}}" {{$permission->user_permission_id != null?'checked' : ''}}>
                                <label class="custom-control-label cursor-pointer" for="{{$permission->name}}">{{$permission->description}}</label>
                            </div>
                        </div>
                @if( ($key+1) % 6 == 0)
                    </div>
                @endif
                @if(count($permissions) == ($key+1))
                    </div>
                @endif
            @endforeach
            <button type="submit" class="btn btn-primary btn-block">
                {{__('role.edit')}}
            </button>
        </form>
    </div>
@endsection
