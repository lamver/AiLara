@extends('layouts.admin')
@section('page_title')
    {{ __('admin.Common configuration') }}
@endsection
@section('page_options')
{{--    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.module.ai-form.create') }}" type="button" class="btn btn-sm btn-outline-success">{{ __('admin.Create') }}</a>
        </div>
    </div>--}}
@endsection
@section('content')
<form method="post" action="{{ route('admin.modules.main.config.save') }}">
    @csrf
    <table class="table">
        <tr>
            <th>
                {{ __('admin.Const name') }}
            </th>
            <th>
                {{ __('admin.Prefix uri') }}
            </th>
            <th>
                {{ __('admin.Use on front') }}
            </th>
        </tr>
    @foreach($moulesConfig as $configValue)
        <tr>
            <td>
              {{ $configValue->const_module_name }}
            </td>
            <td>
                <input name="{{ $configValue->const_module_name }}_prefix_uri" type="text" value="{{ $configValue->prefix_uri }}" class="form form-control"/>
            </td>
            <td>
                <input class="form-check-input" type="checkbox" name="{{ $configValue->const_module_name }}_use_on_front" value="1" @if($configValue->use_on_front) checked @endif id="{{ $configValue->const_module_name }}_use_on_front">
                <label class="form-check-label" for="{{ $configValue->const_module_name }}_use_on_front">
                  {{ __('admin.Use on front') }}
                </label>
            </td>
        </tr>
    @endforeach
    </table>
    <button class="btn btn-primary" type="submit">{{ __('admin.Save') }}</button>
</form>
@endsection
