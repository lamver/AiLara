@extends('layouts.app')
@section('content')
    @include('modules.blog.header-navbar')
    <div class="container">
        <h2>404 {{ __('Page not fount') }}</h2>
        @if($exception->getMessage())
            <span>{{ $exception->getMessage() }}</span>
        @endif
    </div>
@endsection
