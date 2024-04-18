@extends('layouts.admin')
@section('page_title')
    Blog / Category / create
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
{{--{{ $model->title }}--}}
@php
    $route = route('admin.blog.category.store');
    $method = 'POST';
    $btnName = 'Create';
@endphp
@if(isset($model))
    @php
    $route = route('admin.blog.category.update', ['category' => $model->id]);
    $method = 'PUT';
    $btnName = 'Update';
    @endphp
@endif
<form method="post" action="{{ $route }}">
    @method($method)
    @csrf
    @foreach($modelParams as $param)
        @if($param->Extra == 'auto_increment')
            @continue
        @endif

        @if($param->Field == 'parent_id' && count($categoryTree['categories']) == 0)
            @continue
        @endif

        @if($param->Field == 'parent_id')
            <label for="label_{{ $param->Field }}">{{ $param->Field }}</label>
            <select id="label_{{ $param->Field }}" name="{{ $param->Field }}" class="form form-control">
                <option value=""> - no parent cat -</option>
                @foreach($categoryTree['categories'] as $category)
                    <option value="{{$category->id}}">{{$category->id}} {{$category->title}}</option>
                    @if(count($category->childs))
                        @include('admin.modules.blog.category.select-categories', ['childs' => $category->childs, 'value' => isset($model->id) ?? null])
                    @endif
                @endforeach
            </select>
            @continue
        @endif
        @if($param->Field == 'author_id')
            <label for="label_{{ $param->Field }}" class="form-label">{{ $param->Field }}</label>
            <select id="label_{{ $param->Field }}" name="{{ $param->Field }}" class="form form-control">
                @foreach(\App\Models\User::getAuthors() as $author)
                    <option @if(isset($category) && $category->{$param->Field} == $author->id) selected @endif value="{{ $author->id }}">{{ $author->name }}</option>
                @endforeach
            </select>
            @continue
        @endif

        @if(in_array($param->Type, ['bigint unsigned', 'int unsigned']))
            <label for="label_{{ $param->Field }}">{{ $param->Field }}</label>
            <input type="text" class="form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}" value="{{ $model->{$param->Field} ?? '' }}" aria-describedby="emailHelp" placeholder="{{ $param->Comment}}">
            <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
            @continue
        @endif
        @if(in_array($param->Type, ['varchar(255)']))
                <label for="label_{{ $param->Field }}">{{ $param->Field }}</label>
                <input type="text" class="form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}" value="{{ $model->{$param->Field} ?? ''  }}" aria-describedby="emailHelp" placeholder="{{ $param->Comment}}">
                <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
                @continue
        @endif
        @if(in_array($param->Type, ['text', 'longtext']))
            <label for="exampleInputEmail1">{{ $param->Field }}</label>
            <textarea class="form form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}" placeholder="{{ $param->Comment}}">{{ $model->{$param->Field}  ?? '' }}</textarea>
            <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
            @continue
        @endif
        @if(in_array($param->Type, ['timestamp']))
            <label for="label_{{ $param->Field }}">{{ $param->Field }}</label>
            <input type="date" class="form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}" value="{{ $model->{$param->Field} ?? ''  }}" aria-describedby="emailHelp" placeholder="{{ $param->Comment}}">
            <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
            @continue
        @endif
            <br>{{ $param->Type }}<br>
            <label for="exampleInputEmail1">{{ $param->Field }}</label>
            <input type="text" class="form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}" value="{{ $model->{$param->Field} ?? ''  }}" aria-describedby="emailHelp" placeholder="{{ $param->Comment}}">
            <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
    @endforeach
<br>
    <button class="btn btn-primary" type="submit">{{ $btnName }}</button>
</form>
@endsection
