@extends('layouts.admin')
@push('top-scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsoneditor/10.0.1/jsoneditor.css"
          integrity="sha512-iOFdnlwX6UGb55bU5DL0tjWkS/+9jxRxw2KiRzyHMZARASUSwm0nEXBcdqsYni+t3UKJSK7vrwvlL8792/UMjQ=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
@section('content')

    <form method="POST" id="editForm" action="{{ route('telegram-bots.update', $bot->id) }}">
        @method('PUT')
        @csrf
        <div class="mb-3">
            <x-input-label for="botName" :value="__('admin.Bot name')"/>
            <x-text-input id="botName" class="form-control" type="text" name="name"
                          :value="old('name', $bot->name) "
                          required/>
            <x-input-error class="text-danger" :messages="$errors->get('name')" class="mt-2"/>
        </div>

        <div class="mb-3">
            <x-input-label for="botName" :value="__('admin.Bot api key')"/>
            <x-text-input id="botName" class="form-control" type="text" name="token"
                          :value="old('name', $bot->token)"
                          required/>
            <x-input-error class="text-danger" :messages="$errors->get('token')" class="mt-2"/>
        </div>

        <div class="mb-3">
            <label class="form-check-label" for="status">{{ __('admin.Select form') }}</label>
            <select class="form-select" name="form_id" aria-label="">
                <option value="">{{__('admin.Empty')}}</option>
                @foreach($forms as $form)
                    <option @if($form->id === $bot->aiFrom->id) selected @endif value="{{$form->id}}">{{$form->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="btn-group" role="group">
            <button type="submit" class="btn btn-primary">{{ __('admin.Save')}}</button>
        </div>

    </form>

@endsection
