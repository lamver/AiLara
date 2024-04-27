@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('index', \App\Models\Modules\Blog\Category::getBreadCrumbsByUri(Route::current()->uri())))
@section('breadcrumbs-json-ld', Breadcrumbs::view('breadcrumbs::json-ld', 'index', \App\Models\Modules\Blog\Category::getBreadCrumbsByUri(Route::current()->uri())))
@if(isset($_GET) && count($_GET) > 0)
    @push('meta_noindex')
        <meta name="robots" content="noindex">
    @endpush
@endif
@section('header-navbar')
    @include('modules.blog.header-navbar')
@endsection
@push('styles')
    <style>
        .info_block img {
            width: 50px;
        }

        .info_block {
            overflow: hidden;
        }

    </style>
@endpush
@section('stylesheet')
    <link href="https://blogzine.webestica.com/assets/css/style.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
@endsection
@section('header-navbar')
    @include('modules.blog.header-navbar')
@endsection
@section('content')
    <div class="container">
        <h1>{{ $category->title }}</h1>
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    @foreach($posts as $post)
                        <div class="col-md-4">
                            {{--           <h4>{{ $post->title }}</h4>--}}
                            <div class="card mb-3">
                                <img src="{{ \App\Helpers\ImageMaster::resizeImgFromCdn($post->image, 300, 300) }}" class="card-img-top" alt="{{ $post->seo_title }}">
                                <div class="card-body">
                                    <h2 style="font-size: 18px" class="card-title">{{ \App\Helpers\StrMaster::htmlTagClear($post->title) }}</h2>
                                    <p class="card-text">{{ \App\Helpers\StrMaster::htmlTagClear($post->content) }}</p>
                                    <a href="{{ $post->urlToPost }}" title="{{ $post->seo_title }}" class="stretched-link btn btn-link">Читать</a>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            {{ \Illuminate\Support\Carbon::create($post->updated_at)->shortRelativeDiffForHumans(date("Y-m-d h:i:s", time())) }}
                                            &nbsp;
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-dots-fill" viewBox="0 0 16 16">
                                                <path d="M16 8c0 3.866-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7M5 8a1 1 0 1 0-2 0 1 1 0 0 0 2 0m4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                                            </svg>
                                        </small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        {{ $posts->links('pagination.default') }}
        <a href="{{route('blog.post.cat' . '.' . str_replace("/", ".", $rssUrl) . '.rss')}}">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-rss" viewBox="0 0 16 16">
                <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                <path d="M5.5 12a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m-3-8.5a1 1 0 0 1 1-1c5.523 0 10 4.477 10 10a1 1 0 1 1-2 0 8 8 0 0 0-8-8 1 1 0 0 1-1-1m0 4a1 1 0 0 1 1-1 6 6 0 0 1 6 6 1 1 0 1 1-2 0 4 4 0 0 0-4-4 1 1 0 0 1-1-1"/>
            </svg>
        </a>
    </div>
@endsection
