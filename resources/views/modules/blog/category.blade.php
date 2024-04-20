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
                                <img src="https://aisearch.ru/cdn-cgi/image/fit=contain,width=400,height=400,compression=fast/files/0/532194/8_marta_mezdunarodnyi_zenskii_den_narisui_lending_532194.png" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h2 class="card-title">{{ $post->title }}</h2>
                                    <p class="card-text">{{ \Illuminate\Support\Str::limit(strip_tags($post->content)) }}</p>
                                    {{--{{ \App\Models\Modules\Blog\Posts::getUrlPostById($post->id) }}--}}
                                    <a href="{{ $post->urlToPost }}" class="stretched-link btn btn-link">читать</a>
                                    <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        {{ $posts->links('pagination.default') }}
    </div>
@endsection