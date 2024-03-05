@extends('layouts.admin')
@section('stylesheet')
    <link rel="stylesheet" href="/vendor/accessui/accessUi.bt.css">
    <script src="/vendor/accessui/accessUi.bt.js" type="module"></script>
@endsection
@section('content')
    <div class="container">
        @include('accessUi::links')

        @include('accessUi::main')
    </div>
@endsection


