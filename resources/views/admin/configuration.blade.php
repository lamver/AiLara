@extends('layouts.admin')
@section('content')
    <form method="post" class="row g-3">
        @csrf
        @foreach($config as $configName => $data)
            @if ($configName == 'home_module')
                <div class="mb-12">
                    <label class="form-label" style="font-size: 18px">  {{ __($configName) }}</label>
                    <select class="form form-control" name="{{$configName}}">
                        @foreach(\App\Services\Modules\Module::getAllModulesUseOnFront() as $moduleKey => $moduleConf)
                            <option @if($moduleKey == $data) selected @endif value="{{ $moduleKey }}">{{ $moduleConf['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                @continue
                @endif
            @php $theType = gettype($data); @endphp
            @if ($theType === App\Settings\SettingGeneral::TYPE_INT)
                    <div class="mb-12">
                        <label class="form-label" style="font-size: 18px">  {{ __($configName) }}</label>
                        <input class="form-control" name="{{ $configName }}" value="{{ $data }}"/>
                    </div>
            @endif
            @if ($theType === App\Settings\SettingGeneral::TYPE_STRING)
                <div class="mb-12">
                    <label class="form-label" style="font-size: 18px">  {{ __($configName) }}</label>
                    <input class="form-control" name="{{ $configName }}" value="{{ $data }}"/>
                </div>
            @endif
            @if ($theType === App\Settings\SettingGeneral::TYPE_TEXT)
                <div class="mb-12">
                    <label class="form-label" style="font-size: 18px">  {{ __($configName) }}</label>
                    <textarea rows="10" class="form-control" name="{{ $configName }}">{{ $data }}</textarea>
                </div>
            @endif
            @if ($theType === App\Settings\SettingGeneral::TYPE_BOOLEAN)
                <div class="mb-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="{{$configName}}" id="{{$configName}}"
                               @if($data === true) checked @endif>
                        <label class="form-check-label" for="{{$configName}}">{{$configName}}</label>
                    </div>
                </div>
            @endif
            @if ($theType === App\Settings\SettingGeneral::TYPE_ARRAY)
                <label class="form-check-label" for="{{$configName}}">{{$configName}}</label>
                <select class="form-select" name="{{$configName}}">
                    @foreach($data as $key => $item)
                        <option value="{{$key}}" @if($item) selected @endif>{{$key}}</option>
                    @endforeach
                </select>
            @endif
        @endforeach
        <p></p>
        <div class="mb-3">
            <button type="submit" class="btn btn-secondary">{{ __('Apply') }}</button>
        </div>
    </form>

@endsection
