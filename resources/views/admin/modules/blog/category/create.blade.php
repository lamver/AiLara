@extends('layouts.admin')
@section('page_title')
   {{__('admin.Blog')}} / {{__('admin.Category')}} / {{__('admin.Create')}}
@endsection
@section('page_options')
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.blog.category.index') }}" type="button" class="btn btn-sm btn-outline-success">{{ __('admin.All category') }}</a>
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

        <br>
        <label style="font-size: 14px; font-weight: bolder" for="label_{{ $param->Field }}">{{ \App\Models\Modules\Blog\Category::columnName($param->Field) }}</label>

        @if($param->Field == 'parent_id')
            <select id="label_{{ $param->Field }}" name="{{ $param->Field }}" class="form form-control">
                <option value="0"> - no parent cat -</option>
                @foreach($categoryTree['categories'] as $category)
                    @if(isset($model) && $category->id == $model->id)
                        @continue
                    @endif
                    <option @if(isset($model) && $category->id == $model->parent_id) selected @endif value="{{$category->id}}">{{$category->id}} {{$category->title}} {{--{{ $model->id }}--}}</option>
                    @if(count($category->childs))
                        @include('admin.modules.blog.category.select-categories', ['childs' => $category->childs, 'model' => isset($model) ? $model : null ])
                    @endif
                @endforeach
            </select>
            @continue
        @endif
        @if($param->Field == 'author_id')
            <select id="label_{{ $param->Field }}" name="{{ $param->Field }}" class="form form-control">
                @foreach(\App\Models\User::getAuthors() as $author)
                    <option @if(isset($category) && $category->{$param->Field} == $author->id) selected @endif value="{{ $author->id }}">{{ $author->name }}</option>
                @endforeach
            </select>
            @continue
        @endif

        @if(in_array($param->Type, ['bigint unsigned', 'int unsigned']))
            <input type="text" class="form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}" value="{{ $model->{$param->Field} ?? '' }}" aria-describedby="emailHelp" placeholder="{{ $param->Comment}}">
            <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
            @continue
        @endif
        @if(in_array($param->Type, ['varchar(255)']))
                <div class="input-group">
                    <input type="text" class="form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}" value="{{ $model->{$param->Field} ?? ''  }}" aria-describedby="emailHelp" placeholder="{{ $param->Comment}}">
                    <span data-type-id="label_{{ $param->Field }}" class="input-group-text" data-bs-toggle="modal" data-bs-target="#aiModal">
                            &nbsp;<i class="fa fa-child"></i>&nbsp; ai
                    </span>
                </div>
                <small id="emailHelp" class="form-text text-muted d-block">{{ $param->Comment}}</small>
                @continue
        @endif
        @if(in_array($param->Type, ['text', 'longtext']))
                <div class="input-group">
                    <textarea class="form form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}"
                              placeholder="{{ $param->Comment}}">{{ $model->{$param->Field}  ?? '' }}</textarea>
                    <span data-type-id="label_{{ $param->Field }}" class="input-group-text" data-bs-toggle="modal"
                          data-bs-target="#aiModal">
                            &nbsp;<i class="fa fa-child"></i>&nbsp; ai
                    </span>
                </div>
                <small id="emailHelp" class="form-text text-muted d-block">{{ $param->Comment}}</small>
            @continue
        @endif
        @if(in_array($param->Type, ['timestamp']))
            <input type="date" class="form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}" value="{{ $model->{$param->Field} ?? ''  }}" aria-describedby="emailHelp" placeholder="{{ $param->Comment}}">
            <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
            @continue
        @endif
            <input type="text" class="form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}" value="{{ $model->{$param->Field} ?? ''  }}" aria-describedby="emailHelp" placeholder="{{ $param->Comment}}">
            <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
    @endforeach
<br>
    <button class="btn btn-primary" type="submit">{{ $btnName }}</button>
</form>
@endsection
