@extends('layouts.content')

@section('js')

@stop

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">{{__('category.create.title')}}</h1>
        <p class="mb-4"></p>

        <form class="user" method="POST" action="{{route('category.create')}}">
            @csrf
            <div class="form-group">
                <input type="text" class="input-material form-control" id="name" name="name" placeholder="Category Name" required>
                <label for="name" class="input-label">{{__('category.name')}}</label>
            </div>
            <div class="form-group">
                <input type="text" class="input-material form-control" id="priority" name="priority" placeholder="Priority" required>
                <label for="priority" class="input-label">{{__('category.priority')}}</label>
            </div>
            <button type="submit" class="btn btn-primary btn-block">
                {{__('category.add')}}
            </button>
        </form>
    </div>
@endsection
