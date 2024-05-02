@extends('layouts.admin')
@section('page_title')
    {{ __('admin.Blog / Import / Edit') }}
@endsection
@section('page_options')
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.blog.import.index') }}" type="button" class="btn btn-sm btn-outline-success">{{ __('admin.All imports job') }}</a>
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
    $route = route('admin.blog.import.store');
    $method = 'POST';
    $btnName = 'Create';
@endphp
@if(isset($import))
    @php
    $route = route('admin.blog.import.update', ['import' => $import->id]);
    $method = 'PUT';
    $btnName = 'Update';
    @endphp
@endif
<form method="post" action="{{ $route }}">
    @method($method)
    @csrf
    @foreach($modelParams as $param)
        {{ $param->Type }} <br>
        @if($param->Extra == 'auto_increment' || $param->Field == 'result_id_posts')
            @continue
        @endif
        @if($param->Field == 'status')
            <label for="label_{{ $param->Field }}" class="form-label">{{ __('admin.'. str_replace('_', ' ', $param->Field) ) }}</label>
            <select id="label_{{ $param->Field }}" name="{{ $param->Field }}" class="form form-control">
                @foreach(\App\Models\Modules\Blog\Import::IMPORT_STATUS as $importStatus)
                    <option @if(isset($import) && $import->{$param->Field} == $importStatus) selected @endif value="{{ $importStatus }}">{{ \App\Models\Modules\Blog\Import::getStatusName($importStatus) }}</option>
                @endforeach
            </select>
            @continue
        @endif
        @if($param->Field == 'author_id')
            <label for="label_{{ $param->Field }}" class="form-label">{{ __('admin.'. str_replace('_', ' ', $param->Field) ) }}</label>
            <select id="label_{{ $param->Field }}" name="{{ $param->Field }}" class="form form-control">
                @foreach(\App\Models\User::getAuthors() as $author)
                    <option @if(isset($import) && $import->{$param->Field} == $author->id) selected @endif value="{{ $author->id }}">{{ $author->name }}</option>
                @endforeach
            </select>
            @continue
        @endif
        @if($param->Field == 'source_type')
            <label for="label_{{ $param->Field }}" class="form-label">{{ __('admin.'. str_replace('_', ' ', $param->Field) ) }}</label>
            <select id="label_{{ $param->Field }}" name="{{ $param->Field }}" class="form form-control">
                @foreach(\App\Models\Modules\Blog\Import::IMPORT_SOURCE_TYPE as $importSourceType)
                    <option @if(isset($import) && $import->{$param->Field} ==  $importSourceType) selected @endif value="{{ $importSourceType }}">{{ \App\Models\Modules\Blog\Import::getSourceTypeName($importSourceType) }}</option>
                @endforeach
            </select>
            @continue
        @endif
        @if($param->Field == 'what_are_we_doing')
            <label for="label_{{ $param->Field }}" class="form-label">{{ __('admin.'. str_replace('_', ' ', $param->Field) ) }}</label>
            <select id="label_{{ $param->Field }}" name="{{ $param->Field }}" class="form form-control">
                @foreach(\App\Models\Modules\Blog\Import::DOING_VARIANTS as $importDoingVariants)
                    <option @if(isset($import) && $import->{$param->Field} ==  $importDoingVariants) selected @endif value="{{ $importDoingVariants }}">{{ \App\Models\Modules\Blog\Import::getDoingVariantsName($importDoingVariants) }}</option>
                @endforeach
            </select>
            @continue
        @endif
        @if(in_array($param->Field, ['repeating_task', 'cron']))
            <label for="label_{{ $param->Field }}" class="form-label">{{ __('admin.'. str_replace('_', ' ', $param->Field) ) }}</label>
            <select id="label_{{ $param->Field }}" name="{{ $param->Field }}" class="form form-control">
                <option @if(isset($import) && $import->{$param->Field} == '0') selected @endif value="0">No</option>
                <option @if(isset($import) && $import->{$param->Field} == '1') selected @endif value="1">Yes</option>
            </select>
            @continue
        @endif
        @if($param->Field == 'category_id')
            <label for="label_{{ $param->Field }}" class="form-label">{{ __('admin.'. str_replace('_', ' ', $param->Field) ) }}</label>
            <select id="label_{{ $param->Field }}" name="{{ $param->Field }}" class="form form-control">
                @foreach($categoryTree['categories'] as $category)
                    <option @if(isset($import) && $import->{$param->Field} ==  $category->id) selected @endif value="{{$category->id}}">{{$category->id}} {{$category->title}}</option>
                    @if(count($category->childs))
                        @include('admin.modules.blog.category.select-categories', ['childs' => $category->childs, 'value' => isset($import) ?? $import->{$param->Field}])
                    @endif
                @endforeach
            </select>
            @continue
        @endif
        @if(in_array($param->Type, ['bigint unsigned', 'int unsigned']))
            <label for="label_{{ $param->Field }}" class="form-label">{{ __('admin.'. str_replace('_', ' ', $param->Field) ) }}</label>
            <input type="text" class="form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}" value="{{ $import->{$param->Field} ?? '' }}" aria-describedby="emailHelp" placeholder="{{ $param->Comment}}">
            <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
            @continue
        @endif
        @if(in_array($param->Type, ['varchar(255)']))
                <label for="label_{{ $param->Field }}" class="form-label">{{ __('admin.'. str_replace('_', ' ', $param->Field) ) }}</label>
                <input type="text" class="form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}" value="{{ $import->{$param->Field} ?? ''  }}" aria-describedby="emailHelp" placeholder="{{ $param->Comment}}">
                <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
                @continue
        @endif
        @if(in_array($param->Type, ['text', 'longtext']))
            <label for="exampleInputEmail1" class="form-label">{{ __('admin.'. str_replace('_', ' ', $param->Field) ) }}</label>
            <textarea class="form form-control" rows="25" id="label_{{ $param->Field }}" name="{{ $param->Field }}" placeholder="{{ $param->Comment}}">{{ $import->{$param->Field}  ?? '' }}</textarea>
            <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
            @continue
        @endif
        @if(in_array($param->Type, ['timestamp']))
            <label for="label_{{ $param->Field }}" class="form-label">{{ __('admin.'. str_replace('_', ' ', $param->Field) ) }}</label>
            <input type="date" class="form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}" value="{{ $import->{$param->Field} ?? ''  }}" aria-describedby="emailHelp" placeholder="{{ $param->Comment}}">
            <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
            @continue
        @endif
            <br>{{ $param->Type }}<br>
            <label for="exampleInputEmail1" class="form-label">{{ __('admin.'. str_replace('_', ' ', $param->Field) ) }}</label>
            <textarea class="form form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}" placeholder="{{ $param->Comment}}">{{ $import->{$param->Field}  ?? '' }}</textarea>
            <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
    @endforeach
<br>
    <button class="btn btn-primary" type="submit">{{ $btnName }}</button>
</form>
@endsection
