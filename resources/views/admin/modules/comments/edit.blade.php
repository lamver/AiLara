@extends('layouts.admin')
@section('content')
    <form method="POST" action="{{ route('admin.comment.update') }}">
        @method('PUT')
        @csrf
        <input type="hidden" name="comment-id" value="{{$comment->id}}">
        <div class="row mb-3">
            <div class="form-group">
                <label for="title" class="form-label">{{ __('admin.Title') }}</label>
                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror"
                       name="title"
                       value="{{ old('title', $comment->title) }}">

                @error('title')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group">
                <label for="body" class="form-label">{{ __('admin.Body') }}</label>

                <textarea name="body" id="commentBody" cols="30" rows="10" required
                          class="form-control @error('body') is-invalid @enderror">
                    {{$comment->body}}
                </textarea>

                @error('body')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="mb-5 form-group">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="active"
                       id="commentActive"
                       @if($comment->active) checked @endif >
                <label class="form-check-label" for="commentActive">{{__('admin.Status')}}</label>
            </div>
        </div>

        <div class="row mb-0">
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">
                    {{ __('admin.Save') }}
                </button>
                <a href="{{route('admin.comment.index')}}" class="btn btn-danger">
                    {{ __('admin.Cancel') }}
                </a>
            </div>
        </div>

    </form>
@endsection
@push('bottom-scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>

    <script>
        // Initialize SimpleMDE
        let simplemde = new SimpleMDE({
            element: document.getElementById("commentBody"),
            toolbar: ["bold", "italic", "heading", "|", "quote", 'code', 'preview']
        });
    </script>
@endpush
