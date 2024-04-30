@extends('layouts.app')
@section('content')
    @include('modules.blog.header-navbar')
    <div class="container">
        <h2>500 {{ __('Something went wrong. We already know and are fixing it.') }}</h2>
    </div>
@endsection
