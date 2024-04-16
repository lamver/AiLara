@extends('layouts.admin')
@section('page_title')
    Blog / Category
@endsection
@section('page_options')
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.blog.category.create') }}" type="button" class="btn btn-sm btn-outline-success">Add</a>
        </div>
    </div>
@endsection
@section('content')
    <div class="col-md-6">
        <ul id="tree1">
            @foreach($categories as $category)
                <li>
                    <b>{{ $category->id }}</b> {{ $category->title }} <br>
                    <form action="{{ route('admin.blog.category.destroy', $category) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-link" type="submit">Delete</button>
                    </form>
                    <a href="{{ route('admin.blog.category.edit', ['category' => $category->id]) }}">Edit</a>
                    <span class="text-muted">{{ $category->description }}</span>
                    @if(count($category->childs))
                        @include('admin.modules.blog.category.manage-child',['childs' => $category->childs])
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endsection
