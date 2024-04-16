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
    @foreach($modelParams as $param)
        @if($param->Field == 'source_type')
            <b>{{ $param->Field }}</b>: {{ \App\Models\Modules\Blog\Import::getSourceTypeName($import->{$param->Field}) }} <br>
            @continue
        @endif
        @if($param->Field == 'status')
            <b>{{ $param->Field }}</b>: {{ \App\Models\Modules\Blog\Import::getStatusName($import->{$param->Field}) }} <br>
            @continue
        @endif
        @if($param->Field == 'repeating_task')
            <b>{{ $param->Field }}</b>: {{ $import->{$param->Field} == 0 ? 'No' : 'Yes'  }} <br>
            @continue
        @endif
        @if($param->Field == 'cron')
            <b>{{ $param->Field }}</b>: {{ $import->{$param->Field} == 0 ? 'No' : 'Yes'  }} <br>
            @continue
        @endif
        @if($param->Field == 'what_are_we_doing')
            <b>{{ $param->Field }}</b>: {{ \App\Models\Modules\Blog\Import::getDoingVariantsName($import->{$param->Field}) }} <br>
            @continue
            @endif
        <b>{{ $param->Field }}</b>: {{ $import->{$param->Field} }} <br>
    @endforeach
@endsection
