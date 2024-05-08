@extends('layouts.admin')
@section('page_title')
    {{ __('admin.Posts') }}
@endsection
@section('page_options')
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.module.ai-form.create') }}" type="button" class="btn btn-sm btn-outline-success">{{ __('admin.Create') }}</a>
        </div>
    </div>
@endsection
@section('content')
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                @foreach($columns as $column)
                    <th scope="col">{{ __strTrans($column->Field, 'admin') }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($posts as $postData)
                <tr>
                    @foreach($columns as $column)
                        @if($column->Key == 'PRI')
                            <td>{{ $postData->{$column->Field} }}
                                <a href="{{ route('admin.blog.post.edit', ['post' => $postData->{$column->Field}]) }}">{{ __('admin.Edit') }}</a>
                                <a href="{{ route('admin.blog.post.show', ['post' => $postData->{$column->Field}]) }}">{{ __('admin.View') }}</a>
                                <form action="{{ route('admin.blog.post.destroy', $postData) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-link" type="submit" onclick="return confirm('Are you sure?')">{{ __('admin.Delete') }}</button>
                                </form>
                            </td>
                            @continue
                        @endif
                        @if($column->Field == 'author_id')
                           <td> {{ $postData->user->name }}</td>
                                @continue
                        @endif
                        @if($column->Field == 'post_category_id')
                            <td> {{ $postData->category->title }}</td>
                                @continue
                        @endif
                        <td>
                            {{ \Illuminate\Support\Str::limit(strip_tags($postData->{$column->Field}), 50) }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $posts->links('pagination.default') }}
    </div>
@endsection
