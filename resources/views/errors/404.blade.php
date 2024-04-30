@extends('layouts.app')
@section('content')
    @include('modules.blog.header-navbar')
    <div class="container">
        <h2>404 {{ __('Page not fount') }}</h2>
    </div>
@endsection
