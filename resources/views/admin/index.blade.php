@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ __('admin.AiSearch API') }}
        </div>
        <div class="card-body">
            @if($aisUserData['result'] && isset($aisUserData['data']))
                <div>{{ __('admin.Name') }}: {{ $aisUserData['data']['username'] }}</div>
                <div>{{ __('admin.Email') }}: {{ $aisUserData['data']['email'] }}</div>
                <div>{{ __('admin.Balance') }}: {{ number_format(($aisUserData['data']['balance'] / 100)) }} {{ __('admin.Rub') }}.</div>
            @else

                {{ $aisUserData['message'] }}

                <a href="{{ route('admin.configuration') }}">{{ __('admin.Set up') }}</a>
            @endif
        </div>
    </div>
@endsection
