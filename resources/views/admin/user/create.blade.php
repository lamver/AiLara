@extends('layouts.admin')
@section('content')
    <form method="POST" action="{{ route('admin.user.store') }}">
        @csrf

        <div class="row mb-3">
            <div class="form-group">
                <label for="name" class="form-label">{{ __('admin.Name') }}</label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                       name="name"
                       value="{{ old('name') }}" required autocomplete="name" autofocus>

                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group">
                <label for="email" class="form-label">{{ __('admin.Email address') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                       name="email"
                       value="{{ old('email') }}" required autocomplete="email">

                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">

            <div class="form-group">
                <label for="password" class="form-label">{{ __('admin.Password') }}</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                       name="password" required autocomplete="new-password">

                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group">
                <label for="password-confirm" class="form-label">{{ __('admin.Confirm password') }}</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
                       autocomplete="new-password">
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group">
                <label for="avatar" class="form-label">{{ __('admin.Avatar') }}</label>
                <input id="avatar" type="text" class="form-control @error('avatar') is-invalid @enderror" name="avatar">

                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

            </div>
        </div>

        <div class="row mb-3 p-2">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="sys_user" id="sysUser">
                <label class="form-check-label" for="sysUser">{{ __('admin.Sys user') }}</label>
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group">
                <label for="owner-select" class="form-label">{{ __('admin.User role') }}</label>
                <select id="owner-select" class="form-select @error('owner') is-invalid @enderror" name="owner">
                    @foreach($owners as $owner)
                        <option value="{{$owner->original_id}}">{{$owner->original_id}}</option>
                    @endforeach
                </select>
                @error('owner')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="row mb-0">
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">
                    {{ __('admin.Add') }}
                </button>
                <a href="{{route('admin.user.index')}}" class="btn btn-danger">
                    {{ __('admin.Cancel') }}
                </a>
            </div>
        </div>
    </form>
@endsection
