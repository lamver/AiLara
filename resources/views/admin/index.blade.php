@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            AiSearch API
        </div>
        <div class="card-body">
            @if($aisUserData['result'] && isset($aisUserData['data']))
                <div>Nane: {{ $aisUserData['data']['username'] }}</div>
                <div>Email: {{ $aisUserData['data']['email'] }}</div>
                <div>Balance: {{ number_format(($aisUserData['data']['balance'] / 100)) }} р.</div>
            @else

                {{ $aisUserData['message'] }}

                <a href="{{ route('admin.configuration') }}">{{ __('Set up') }}</a>
            @endif
        </div>
    </div>
@endsection
