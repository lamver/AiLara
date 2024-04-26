@extends('layouts.admin')
@section('content')
    @foreach($updateLog as $log)
        {{ $log }} <br>
    @endforeach
@endsection
