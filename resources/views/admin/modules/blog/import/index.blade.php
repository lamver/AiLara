@extends('layouts.admin')
@section('page_title')
    Blog / Imports
@endsection
@section('page_options')
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.blog.import.create') }}" type="button" class="btn btn-sm btn-outline-success">Add</a>
        </div>
    </div>
@endsection
@section('content')
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
            @foreach($imports as $postData)
                <tr>
                    @foreach($columns as $column)
                        @if($column->Key == 'PRI')
                            <td>{{ $postData->{$column->Field} }}
                                <a href="{{ route('admin.blog.import.edit', ['import' => $postData->{$column->Field}]) }}">Edit</a>
                                <a href="{{ route('admin.blog.import.show', ['import' => $postData->{$column->Field}]) }}">View</a>
                                <form action="{{ route('admin.blog.import.destroy', $postData) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-link" type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
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
