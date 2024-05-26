@extends('layouts.admin')
@push('top-scripts')

@endpush
@section('page_title')
    {{ __('admin.Ai Form') }} / {{ __('admin.Settings') }}
@endsection
@section('content')
    <form method="post" action="{{ route('admin.module.ai-form.settings.update') }}">
        @csrf
        @foreach($settings as $settingName => $settingValue)
            @if($settingName == 'home_page_category_ids')
                @php $categories = \App\Models\Modules\Blog\Category::where('parent_id', '=', null)->get(); @endphp
                @include('admin.modules.blog.category.for_selected.main', ['categories' => $categories, 'selected_ids' => json_decode($settingValue) ?? [] ])
                @continue
            @endif
            @if($settingName == 'home_page_view_forms_ids')
                @php $forms = \App\Models\Modules\AiForm\AiForm::query()->select(['id', 'name'])->get();  @endphp
                @foreach($forms as $form)
                        <input type="checkbox" name="form_ids[]" @if(!empty($settingValue) && in_array($form->id, json_decode($settingValue, true))) checked @endif value="{{ $form->id }}"> {{ $form->name }}
                @endforeach
            @continue
            @endif
            @if (gettype($settingValue) === \App\Settings\Data::TYPE_INT)
                <div class="mb-12">
                    <label class="form-label" style="font-size: 18px">{{__strTrans($settingName, 'admin')}}</label>
                    <input class="form-control" name="{{ $settingName }}" value="{{ $settingValue }}"/>
                </div>
            @endif
            @if (gettype($settingValue) === \App\Settings\Data::TYPE_BOOLEAN)
                    <div class="mb-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="{{$settingName}}" id="{{$settingName}}"
                                   @if($settingValue === true) checked @endif>
                            <label class="form-check-label" for="{{$settingName}}">{{__strTrans($settingName, 'admin')}}</label>
                        </div>
                    </div>
            @endif
            @if (gettype($settingValue) === \App\Settings\Data::TYPE_STRING)
                <div class="mb-12">
                    <label class="form-label" style="font-size: 18px">{{__strTrans($settingName, 'admin')}}</label>
                    <input class="form-control" name="{{ $settingName }}" value="{{ $settingValue }}"/>
                </div>
            @endif
        @endforeach
        <p></p>
        <button class="btn btn-primary" type="submit">{{ __('admin.Save') }}</button>
    </form>
@endsection
