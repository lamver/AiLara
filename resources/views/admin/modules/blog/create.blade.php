@extends('layouts.admin')
@section('page_title')
    Blog / Posts / Create
@endsection
@section('page_options')
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.blog.post.index') }}" type="button" class="btn btn-sm btn-outline-success">All posts</a>
{{--            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>--}}
        </div>
{{--        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
            <span data-feather="calendar"></span>
            This week
        </button>--}}
    </div>
@endsection
@section('content')
{{--{{ $post->title }}--}}
@php
    $route = route('admin.blog.post.store');
    $method = 'POST';
    $btnName = 'Create';
@endphp
@if(isset($post))
    @php
    $route = route('admin.blog.post.update', ['post' => $post->id]);
    $method = 'PUT';
    $btnName = 'Update';
    @endphp
@endif
<form method="post" action="{{ $route }}">
    @method($method)
    @csrf
    @foreach($modelParams as $param)
        {{ $param->Type }} <br>
        @if($param->Extra == 'auto_increment')
            @continue
        @endif
        @if($param->Field == 'status')
            <label for="label_{{ $param->Field }}">{{ $param->Field }}</label>
            <select id="label_{{ $param->Field }}" name="{{ $param->Field }}" class="form form-control">
                @foreach(\App\Models\Modules\Blog\Posts::STATUS as $postStatus)
                    <option @if(isset($post) && $post->{$param->Field} ==  $postStatus) selected @endif value="{{ $postStatus }}">{{ $postStatus }}</option>
                @endforeach
            </select>
            @continue
        @endif
        @if($param->Field == 'post_category_id')
            <label for="label_{{ $param->Field }}">{{ $param->Field }}</label>
            <select id="label_{{ $param->Field }}" name="{{ $param->Field }}" class="form form-control">
                <option value=""> - no parent cat -</option>
                @foreach($categoryTree['categories'] as $category)
                    <option @if(isset($post) && $post->{$param->Field} ==  $category->id) selected @endif value="{{$category->id}}">{{$category->id}} {{$category->title}}</option>
                    @if(count($category->childs)) {{--{{ print_r($category->childs[0]->title) }}--}}
                    @include('admin.modules.blog.category.select-categories', ['childs' => $category->childs, 'value' => isset($post) ?? $post->{$param->Field}])
                    @endif
                @endforeach
            </select>
            @continue
        @endif
        @if(in_array($param->Type, ['bigint unsigned', 'int unsigned']))
            <label for="label_{{ $param->Field }}">{{ $param->Field }}</label>
            <input type="text" class="form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}" value="{{ $post->{$param->Field} ?? '' }}" aria-describedby="emailHelp" placeholder="{{ $param->Comment}}">
            <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
            @continue
        @endif
        @if(in_array($param->Type, ['varchar(255)']))
                <label for="label_{{ $param->Field }}">{{ $param->Field }}</label>
                <input type="text" class="form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}" value="{{ $post->{$param->Field} ?? ''  }}" aria-describedby="emailHelp" placeholder="{{ $param->Comment}}">
                <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
                @continue
        @endif
        @if(in_array($param->Type, ['text', 'longtext']))
            <label for="exampleInputEmail1">{{ $param->Field }}</label>
            <textarea class="form form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}" placeholder="{{ $param->Comment}}">{{ $post->{$param->Field}  ?? '' }}</textarea>
            <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
            @continue
        @endif
        @if(in_array($param->Type, ['timestamp']))
            <label for="label_{{ $param->Field }}">{{ $param->Field }}</label>
            <input type="date" class="form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}" value="{{ $post->{$param->Field} ?? ''  }}" aria-describedby="emailHelp" placeholder="{{ $param->Comment}}">
            <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
            @continue
        @endif
            <br>{{ $param->Type }}<br>
            <label for="exampleInputEmail1">{{ $param->Field }}</label>
            <textarea class="form form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}" placeholder="{{ $param->Comment}}">{{ $post->{$param->Field}  ?? '' }}</textarea>
            <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
    @endforeach
<br>
    <button class="btn btn-primary" type="submit">{{ $btnName }}</button>
</form>
@endsection
