@extends('layouts.admin')
@section('content')
    <div>Nane: {{ $result['data']['username'] }}</div>
    <div>Email: {{ $result['data']['email'] }}</div>
    <div>Balance: {{ number_format(($result['data']['balance'] / 100)) }} р.</div>

@endsection
