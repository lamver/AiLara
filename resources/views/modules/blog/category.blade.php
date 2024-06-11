@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('index', \App\Models\Modules\Blog\Category::getBreadCrumbsByUri(Route::current()->uri())))
@section('breadcrumbs-json-ld', Breadcrumbs::view('breadcrumbs::json-ld', 'index', \App\Models\Modules\Blog\Category::getBreadCrumbsByUri(Route::current()->uri())))
@if(isset($_GET) && count($_GET) > 0)
    @push('meta_noindex')
        <meta name="robots" content="noindex">
    @endpush
@endif
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
@endsection
@section('top-sub-app-navbar')
    @include('modules.blog.header-navbar')
@endsection
@section('content')
    <div class="container">
        <h1>{{ $category->title }}</h1>
        <div class="row">
            <div class="col-md-8">
                <div class="row" id="autoLoad">
                    @include('modules.blog.category_part', ['post' => $posts])
                </div>
            </div>
        </div>
        @if($settingBlog->pagination_type == \App\Settings\SettingBlog::PAGINATION_TYPE[0])
            {{ $posts->links('pagination.default') }}
        @endif
        <a href="{{route(Route::currentRouteName().'rss')}}">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-rss" viewBox="0 0 16 16">
                <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                <path d="M5.5 12a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m-3-8.5a1 1 0 0 1 1-1c5.523 0 10 4.477 10 10a1 1 0 1 1-2 0 8 8 0 0 0-8-8 1 1 0 0 1-1-1m0 4a1 1 0 0 1 1-1 6 6 0 0 1 6 6 1 1 0 1 1-2 0 4 4 0 0 0-4-4 1 1 0 0 1-1-1"/>
            </svg>
        </a>
    </div>
@endsection
@push('bottom-scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let lazyLoadImages = document.querySelectorAll('.lazy-load-image');

            lazyLoadImages.forEach(function(element) {
                if (element.tagName === 'IMG' && element.getAttribute('data-src')) {
                    element.setAttribute('src', element.getAttribute('data-src'));
                } else if (element.tagName === 'DIV' && element.getAttribute('data-bg-url')) {
                    element.style.backgroundImage = `url(${element.getAttribute('data-bg-url')})`;
                }
            });
        });
    </script>

    @if($settingBlog->pagination_type == \App\Settings\SettingBlog::PAGINATION_TYPE[1])
        <script>

            const HEADER = {
                "Content-Type": "application/json",
                "Accept": "application/json",
            };

            let posts = JSON.parse('{!! $posts->toJson() !!}');
            let steps = posts.current_page;
            let scrollTimeout;

            window.addEventListener('scroll', () => {
                console.log(posts.last_page, steps)
                if (!scrollTimeout && posts.last_page > steps) {
                    scrollTimeout = setTimeout(() => {
                        handleScroll();
                        scrollTimeout = null;
                    }, 2000);
                }
            });

            function fetchPostsPage() {
                return fetch(posts.path + "?page=" + steps, {headers: HEADER})
                    .then(response => response.text());
            }

            const handleScroll = () => {
                const scrollHeight = document.documentElement.scrollHeight;
                const scrollTop = document.documentElement.scrollTop;

                if (scrollTop > scrollHeight / 2) {
                    fetchPostsPage().then(text => {
                        let autoLoad = document.getElementById('autoLoad');
                        autoLoad.insertAdjacentHTML('beforeend', text);
                        steps++;
                    });
                }
            };

        </script>
    @endif
@endpush
