@extends('layouts.admin')
@section('page_title')
    {{__('admin.blog.settings')}}
@endsection
@section('content')
    <form method="post" action="{{ route('admin.blog.settings.update') }}">
        @method('PUT')
        @csrf

        <div class="mb-3">
            <div>
                <x-input-label for="name" :value="__('admin.Api secret key rss export')" />
                <x-text-input id="randomStringInput"  name="api_secret_key_rss_export" value="{{$settings->api_secret_key_rss_export}}" type="text" class="form-control" required />
                <x-input-error class="mt-2" :messages="$errors->get('api_secret_key_rss_export')" />
            </div>
        </div>

        <div class="mb-12 mb-3">
            <label class="form-label" style="font-size: 18px">{{ __('admin.Pagination type on category page') }}</label>
            <select class="form form-control" name="pagination_type">
                @foreach(\App\Settings\SettingBlog::PAGINATION_TYPE as $paginationType)
                    <option @if($paginationType == $settings->pagination_type) selected @endif value="{{ $paginationType }}">  {{ __strTrans($paginationType, 'admin') }} </option>
                @endforeach
            </select>
        </div>

        <div class="mb-12 mb-3">
            <div class="form-check form-switch">
                <input id="loadPostsOnPostPage" class="form-check-input" type="checkbox" name="load_posts_on_post_page" @if($settings->load_posts_on_post_page === true) checked @endif>
                <label class="form-check-label" for="loadPostsOnPostPage" >{{ __('admin.Load posts on post page') }}</label>
            </div>
        </div>


        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row mb-0">
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">
                    {{ __('admin.Save') }}
                </button>
            </div>
        </div>

    </form>
@endsection


