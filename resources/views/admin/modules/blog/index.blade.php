@extends('layouts.admin')
@section('page_options')
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.blog.post.create') }}" type="button" class="btn btn-sm btn-outline-success">Add</a>
{{--            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>--}}
        </div>
{{--        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
            <span data-feather="calendar"></span>
            This week
        </button>--}}
    </div>
@endsection
@section('content')
{{--    <h2>Section title</h2>--}}
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                @foreach($columns as $column)
                    <th scope="col">{{ $column->Field }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($posts as $postData)
                <tr>
                    @foreach($columns as $column)
                        @if($column->Key == 'PRI')
                            <td>{{ $postData->{$column->Field} }}
                                <a href="{{ route('admin.blog.post.edit', ['post' => $postData->{$column->Field}]) }}">Edit</a>
                                <a href="{{ route('admin.blog.post.show', ['post' => $postData->{$column->Field}]) }}">View</a>
                                <a href="{{ route('admin.blog.post.destroy', ['post' => $postData->{$column->Field}]) }}">Delete</a>
                            </td>
                            @continue
                        @endif
                    <td>{{ $postData->{$column->Field} }}</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
