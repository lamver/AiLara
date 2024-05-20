@extends('layouts.admin')
@section('content')
    @foreach($optimizeLog as $log)
        {{ $log }} <br>
    @endforeach
@endsection
