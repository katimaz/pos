@extends('layouts.content')

@section('js')

@stop

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">{{__('category.edit.title')}}</h1>
        <p class="mb-4"></p>

        <form method="POST" action="{{route('category.update')}}">
            @csrf
            <div class="form-group">
                <input type="hidden" class="form-control" name="id" value="{{$category->id}}">
                <input type="text" class="input-material  form-control" id="name" name="name" placeholder="Enter Category Name" value="{{$category->name}}" required>
                <label for="name" class="input-label">{{__('category.name')}}</label>
            </div>
            <div class="form-group">
                <input type="text" class="input-material form-control" id="priority" name="priority" placeholder="Priority" value="{{$category->priority}}" required>
                <label for="priority" class="input-label">{{__('category.priority')}}</label>
            </div>
            <button type="submit" class="btn btn-primary btn-block">
                {{__('category.edit')}}
            </button>
        </form>
    </div>
@endsection
