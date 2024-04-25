@extends('layouts.admin')
@section('content')
    <form method="POST" action="{{ route('admin.user.update', $user->id) }}">
        @method('PUT')
        @csrf

        <input type="hidden" name="user_id" value="{{$user->id}}">

        <div class="row mb-3">
            <div class="form-group">
                <label for="name" class="form-label">{{ __('Name') }}</label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                       name="name"
                       value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>

                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group">
                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                       name="email"
                       value="{{ old('email', $user->email) }}" required autocomplete="email">

                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group">
                <label for="new-password" class="form-label">{{ __('new password') }}</label>
                <input id="new-password" type="password"
                       class="form-control @error('new_password') is-invalid @enderror"
                       name="new_password" autocomplete="new-password">

                @error('new_password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group">
                <label for="owner-select" class="form-label">{{ __('user_role') }}</label>
                <select id="owner-select" class="form-select @error('owner') is-invalid @enderror" name="owner">
                    @foreach($owners as $owner)
                        <option value="{{$owner->original_id}}"
                                @if(!$owner && ($user->getInheritanceParent()->original_id == $owner->original_id)) selected @endif>
                            {{$owner->original_id}}
                        </option>
                    @endforeach
                </select>
                @error('owner')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="mb-5 form-group">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="status"
                       @if($user->status) checked @endif id="userStatus">
                <label class="form-check-label" for="userStatus">{{__('user_status')}}</label>
            </div>
        </div>

        <div class="row mb-0">
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">
                    {{ __('save') }}
                </button>
                <a href="{{route('admin.user.index')}}" class="btn btn-danger">
                    {{ __('cancel') }}
                </a>
            </div>
        </div>

    </form>
@endsection
