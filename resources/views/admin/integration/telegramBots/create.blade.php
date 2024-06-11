@extends('layouts.admin')
@push('top-scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsoneditor/10.0.1/jsoneditor.css"
          integrity="sha512-iOFdnlwX6UGb55bU5DL0tjWkS/+9jxRxw2KiRzyHMZARASUSwm0nEXBcdqsYni+t3UKJSK7vrwvlL8792/UMjQ=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
@section('content')

    <form method="post" action="{{ route('admin.telegram-bots.store') }}">
        @csrf
        <div class="mb-3">
            <x-input-label for="botName" :value="__('admin.Bot name')"/>
            <x-text-input id="botName" class="form-control" type="text" name="name" :value="old('name')"
                          required/>
            <x-input-error class="text-danger" :messages="$errors->get('name')" class="mt-2"/>
        </div>

        <div class="mb-3">
            <x-input-label for="botName" :value="__('admin.Bot api key')"/>
            <x-text-input id="botName" class="form-control" type="text" name="token" :value="old('token')"
                          required/>
            <x-input-error class="text-danger" :messages="$errors->get('token')" class="mt-2"/>
        </div>

        <div class="mb-3">
            <label class="form-check-label" for="formId">{{ __('admin.Select form') }}</label>
            <select class="form-select" id="formId" name="form_id" >
                <option value="">{{__('admin.Empty')}}</option>
                @foreach($forms as $form)
                    <option value="{{$form->id}}">{{$form->name}}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">{{ __('admin.Save')}}</button>

    </form>
@endsection
