@extends('layouts.admin')
@section('page_title')
    {{__('admin.blog.settings')}}
@endsection
@section('content')
    <form method="post" action="{{ route('admin.blog.settings.update') }}">
        @method('PUT')
        @csrf

        <div class="mb-3">
            <div>
                <x-input-label for="name" :value="__('admin.Api secret key rss export')" />
                <x-text-input id="randomStringInput"  name="api_secret_key_rss_export" value="{{$settings->api_secret_key_rss_export}}" type="text" class="form-control" required />
                <x-input-error class="mt-2" :messages="$errors->get('api_secret_key_rss_export')" />
            </div>
        </div>

        <div class="mb-3">
            <label class="form-check-label" for="telegramBotId">{{ __('admin.Telegram bot') }}</label>
            <select class="form-select" id="telegramBotId" name="telegram_bot">
                <option value="">{{__('admin.Empty')}}</option>
                @foreach($telegramBots as $bot)
                    <option @if((int)$settings->telegram_bot === (int)$bot->id) selected @endif value="{{$bot->id}}">{{$bot->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="row mb-0">
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">
                    {{ __('save') }}
                </button>
            </div>
        </div>

    </form>
@endsection


