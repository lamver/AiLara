@extends('layouts.admin')
@section('page_title')
    {{ __('admin.Ai forms') }}
@endsection
@section('page_options')
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.module.ai-form.create') }}" type="button" class="btn btn-sm btn-outline-success">{{ __('admin.Create form') }}</a>
        </div>
    </div>
@endsection
@section('content')
    <table class="table">
        <tr>
            <td>
                {{ __('admin.Name') }}
            </td>
            <td>
                {{ __('admin.Use default') }}
            </td>
            <td>
                {{ __('admin.Delete') }}
            </td>
        </tr>
    @foreach($aiFormsConfig as $aiFormConfig)
        <tr>
            <td> # {{ $aiFormConfig->id }}
                <a href="{{ route('admin.module.ai-form.edit', $aiFormConfig) }}">
                    {{ $aiFormConfig->name }}
                </a>
            </td>
            <td>
                {{ $aiFormConfig->use_default == true ?? __('admin.Yes') }}
                @if(!$aiFormConfig->use_default)
                <form action="{{ route('admin.module.ai-form.update', $aiFormConfig) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-link" name="use_default" value="1" type="submit">
                        {{ __('admin.Set default') }}
                    </button>
                </form>
                @endif
            </td>
            <td>
                <form action="{{ route('admin.module.ai-form.destroy', $aiFormConfig) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-link" type="submit" onclick="return confirm('{{ __('admin.Are you sure') }} ?')">{{ __('admin.Delete') }}</button>
                </form>
            </td>
        </tr>
    @endforeach
    </table>
@endsection
