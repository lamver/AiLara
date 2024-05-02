@extends('layouts.admin')
@section('content')
    <form method="post" action="{{ route('admin.configuration.robots_txt.save') }}">
        @csrf
        <textarea class="form form-control" rows="20" name="robotsTxtContent">{{ $robotsTxtContent }}</textarea>
{{--        @foreach($config as $configName => $data)
            @if ($data['type'] === \App\Models\AiLaraConfig::TYPE_INT)
                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" style="font-size: 18px">{{ $data['label'] }}</label>
                <input class="form-control" name="{{ $configName }}" value="{{ $data['value'] }}"/>
            @endif
            @if ($data['type'] === \App\Models\AiLaraConfig::TYPE_STRING)
                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" style="font-size: 18px">{{ $data['label'] }}</label>
                <input class="form-control" name="{{ $configName }}" value="{{ $data['value'] }}"/>
            @endif
            @if ($data['type'] === \App\Models\AiLaraConfig::TYPE_TEXT)
                <label class="block font-medium text-lg text-gray-700 dark:text-gray-300" style="font-size: 18px">{{ $data['label'] }}</label>
                <textarea rows="10" class="form-control" name="{{ $configName }}">{{ $data['value'] }}</textarea>
            @endif
        @endforeach--}}
        <p></p>
        <button type="submit" class="btn btn-secondary">{{ __('admin.Save') }}</button>
    </form>

@endsection
