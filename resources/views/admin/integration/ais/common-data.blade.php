@extends('layouts.admin')
@section('content')
    @if(isset($result['data']))
        <div>{{ __('admin.Name') }}: {{ $result['data']['username'] }}</div>
        <div>{{ __('admin.Nmail') }}: {{ $result['data']['email'] }}</div>
        <div>{{ __('admin.Balance') }}: {{ number_format(($result['data']['balance'] / 100)) }} {{ __('admin.Rub') }}.</div>
    @endif
@endsection
