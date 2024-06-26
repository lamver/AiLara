@extends('layouts.app')
@section('stylesheet')

@endsection
@section('styles')
    <style>
        .info-block-top {
            background-position: center center;
            background-repeat: no-repeat;
            position: relative;
            margin-bottom: 10px;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 2;
        }
        .info-block-top:before {
            content: "";
            position: absolute;
            height: 80%;
            width: 100%;
            bottom: 0;
            left: 0;
            right: 0;
            background-image: -webkit-gradient(linear, left top, left bottom, from(transparent), to(black));
            background-image: linear-gradient(180deg, transparent, black);
            z-index: 0;
        }

        h2 a {
            color: white;
            text-decoration: none;
            max-width: 90%;
        }

        h2 {
            bottom: 40%;
            left: 5%;
            position: absolute;
        }

        .text-overlay {
            z-index: 2;
        }

        .nav-item.dropdown:hover .dropdown-menu {
            display: block;
        }
    </style>
@endsection
@section('header-navbar')
@endsection
@section('content')
    @include('modules.blog.header-navbar')
    <div class="container">
        <h1>{{ $settings->app_name }}</h1>
        <div class="row">
            @if(isset($topFourPosts[0]) && !empty($topFourPosts[0]->title))
                <div class="col-md-6">
                    <div class="shadow-sm rounded info-block-top lazy-load-image" data-bg-url="{{ \App\Helpers\ImageMaster::resizeImgFromCdn($topFourPosts[0]->image, 800, 900) }}" style="height: 520px; background-image: url('{{ \App\Helpers\ImageMaster::getRandomSprite() }}'); background-size: 100%;">
                        <h2 class="text-overlay" style="position: absolute">
                            <a class="stretched-link" href="{{ $topFourPosts[0]->urlToPost }}">{{ \App\Helpers\StrMaster::htmlTagClear($topFourPosts[0]->title, 50) }}</a>
                        </h2>
                        <div class="text-overlay" style="position: absolute; bottom: 10%; left: 5%; color: white; font-size: 1.2em;">
                            {{ \App\Helpers\StrMaster::htmlTagClear($topFourPosts[0]->content, 70) }}
                            <p></p>
                            <div class="text-muted" style="font-size: 0.8em;">
                                <img width="30" height="30" class="rounded-circle" src="{{ \App\Models\User::getAvatarUrl($topFourPosts[0]->user) }}"/> &nbsp;
                                {{ $topFourPosts[0]->user->name }}
                                {{ \Illuminate\Support\Carbon::create($topFourPosts[0]->updated_at)->shortRelativeDiffForHumans(date("Y-m-d h:i:s", time())) }}
                                <a href="{{ \App\Models\Modules\Blog\Category::getCategoryUrlById($topFourPosts[0]->post_category_id) }}">
                                    {{ $topFourPosts[0]->category->title }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-md-6">
                <div class="row">
                    @if(isset($topFourPosts[1]) && !empty($topFourPosts[1]->title))
                        <div class="col-md-12 top-four-post">
                            <div class="shadow-sm rounded info-block-top lazy-load-image" data-bg-url="{{ \App\Helpers\ImageMaster::resizeImgFromCdn($topFourPosts[1]->image, 800, 900) }}" style="height: 250px; background-image: url('{{ \App\Helpers\ImageMaster::getRandomSprite() }}'); background-size: 100%;">
                                <h2 class="text-overlay" style="position: absolute; font-size: 1.6em; bottom: 50%">
                                    <a class="stretched-link" href="{{ $topFourPosts[1]->urlToPost }}">{{ \App\Helpers\StrMaster::htmlTagClear($topFourPosts[1]->title, 50) }}</a>
                                </h2>
                                <div class="text-overlay" style="position: absolute; bottom: 10%; left: 5%; color: white; font-size: 1.2em;">
                                    {{ \App\Helpers\StrMaster::htmlTagClear($topFourPosts[1]->content, 70) }}
                                    <p></p>
                                    <div class="text-muted" style="font-size: 0.8em;">
                                        <img width="30" height="30" class="rounded-circle" src="{{ \App\Models\User::getAvatarUrl($topFourPosts[0]->user) }}"/> &nbsp;
                                        {{ $topFourPosts[0]->user->name }}
                                        {{ \Illuminate\Support\Carbon::create($topFourPosts[0]->updated_at)->shortRelativeDiffForHumans(date("Y-m-d h:i:s", time())) }}
                                        <a href="{{ \App\Models\Modules\Blog\Category::getCategoryUrlById($topFourPosts[0]->post_category_id) }}">
                                            {{ $topFourPosts[0]->category->title }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(isset($topFourPosts[2]) && !empty($topFourPosts[2]->title))
                        <div class="col-md-6 top-four-post">
                            <div class="shadow-sm rounded info-block-top lazy-load-image" data-bg-url="{{ \App\Helpers\ImageMaster::resizeImgFromCdn($topFourPosts[2]->image, 800, 900) }}" style="height: 250px; background-image: url('{{ \App\Helpers\ImageMaster::getRandomSprite() }}'); background-size: 100%;">
                                <h2 class="text-overlay" style="font-size: 1.2em; bottom: 50%">
                                    <a class="stretched-link" href="{{ $topFourPosts[2]->urlToPost }}">{{ \App\Helpers\StrMaster::htmlTagClear($topFourPosts[2]->title, 50) }}</a>
                                </h2>
                                <div class="text-overlay" style="position: absolute; bottom: 10%; left: 5%; color: white; font-size: 0.8em;">
                                    {{ \App\Helpers\StrMaster::htmlTagClear($topFourPosts[2]->content, 70) }}
                                    <p></p>
                                    <div class="text-muted" style="font-size: 1.0em;">
                                        <img width="30" height="30" class="rounded-circle" src="{{ \App\Models\User::getAvatarUrl($topFourPosts[0]->user) }}"/> &nbsp;
                                        {{ $topFourPosts[0]->user->name }}
                                        {{ \Illuminate\Support\Carbon::create($topFourPosts[0]->updated_at)->shortRelativeDiffForHumans(date("Y-m-d h:i:s", time())) }}
                                        <a title="{{ $topFourPosts[0]->category->seo_title }}" href="{{ \App\Models\Modules\Blog\Category::getCategoryUrlById($topFourPosts[0]->post_category_id) }}">
                                            {{ $topFourPosts[0]->category->title }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(isset($topFourPosts[3]) && !empty($topFourPosts[3]->title))
                        <div class="col-md-6 top-four-post">
                            <div class="shadow-sm rounded info-block-top lazy-load-image" data-bg-url="{{ \App\Helpers\ImageMaster::resizeImgFromCdn($topFourPosts[3]->image, 300, 300) }}" style="height: 250px; background-image: url('{{ \App\Helpers\ImageMaster::getRandomSprite() }}'); background-size: 100%;">
                                <h2 class="text-overlay" style="position: absolute; font-size: 1.2em; bottom: 50%">
                                    <a class="stretched-link" href="{{ $topFourPosts[3]->urlToPost }}">{{ \App\Helpers\StrMaster::htmlTagClear($topFourPosts[3]->title, 50) }}</a>
                                </h2>
                                <div class="text-overlay" style="position: absolute; bottom: 10%; left: 5%; color: white; font-size: 0.8em;">
                                    {{ \App\Helpers\StrMaster::htmlTagClear($topFourPosts[3]->content, 70) }}
                                    <p></p>
                                    <div class="text-muted" style="font-size: 1.0em;">
                                        <img width="30" height="30" class="rounded-circle" src="{{ \App\Models\User::getAvatarUrl($topFourPosts[0]->user) }}"/> &nbsp;
                                        {{ $topFourPosts[0]->user->name }}
                                        {{ \Illuminate\Support\Carbon::create($topFourPosts[0]->updated_at)->shortRelativeDiffForHumans(date("Y-m-d h:i:s", time())) }}
                                        <a title="{{ $topFourPosts[0]->category->seo_title }}" href="{{ \App\Models\Modules\Blog\Category::getCategoryUrlById($topFourPosts[0]->post_category_id) }}">
                                            {{ $topFourPosts[0]->category->title }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <p></p>
        <div class="row">
            <div class="col-md-9">
                <h3>Главные события сегодняшнего дня</h3>
                <div class="row posts-wall">
                    @foreach($topPostsDifferentCategories as $post)
                        <div class="col-md-6">
                            <div class="card mb-3">
                                @if(!empty($post->image))
                                <img src="{{ \App\Helpers\ImageMaster::getRandomSprite() }}" data-src="{{ \App\Helpers\ImageMaster::resizeImgFromCdn($post->image, 500, 500) }}" class="card-img-top lazy-load-image" alt="{{ $post->seo_title }}">
                                @endif
                                <div class="card-body">
                                    <h3 class="card-title">{{ \App\Helpers\StrMaster::htmlTagClear($post->title) }}</h3>
                                    <p class="card-text">{{ \App\Helpers\StrMaster::htmlTagClear($post->content, 150) }}</p>
                                    <a href="{{ $post->urlToPost }}" title="{{ $post->seo_title }}" class="stretched-link btn btn-link">Читать</a>
                                    <p></p>
                                    <div class="text-muted" style="font-size: 1.0em;">
                                        <img width="30" height="30" class="rounded-circle" src="{{ \App\Models\User::getAvatarUrl($post->user) }}"/> &nbsp;
                                        {{ $post->user->name }}
                                        {{ \Illuminate\Support\Carbon::create($post->updated_at)->shortRelativeDiffForHumans(date("Y-m-d h:i:s", time())) }}
                                        <a title="{{ $post->category->seo_title }}" href="{{ \App\Models\Modules\Blog\Category::getCategoryUrlById($post->post_category_id) }}">
                                            {{ $post->category->title }}
                                        </a> &nbsp;
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-dots-fill" viewBox="0 0 16 16">
                                            <path d="M16 8c0 3.866-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7M5 8a1 1 0 1 0-2 0 1 1 0 0 0 2 0m4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-3">
            </div>
        </div>
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

    @if (isset($post))
    let lastPostId = {{ $post->id }};

    const handleScroll = () => {
        const scrollHeight = document.documentElement.scrollHeight;
        const scrollTop = document.documentElement.scrollTop;
        const clientHeight = document.documentElement.clientHeight;

        if (scrollTop > scrollHeight / 2) { // если прокрутили больше чем на 50%
            fetch(`/api/v1/module/blog/load?lastId=${lastPostId}&limit=5`)
                .then(response => response.json())
                .then(data => {
                    if (data.result) {
                        data.data.posts.forEach(post => {
                            const postDiv = document.createElement('div');
                            postDiv.className = 'col-md-6';
                            postDiv.innerHTML = `
                            <div class="card mb-3">
                                <img src="${post.image}" class="card-img-top lazy-load-image" alt="">
                                <div class="card-body">
                                    <h3 class="card-title">${post.title}</h3>
                                    <p class="card-text">${post.content}</p> #${post.id}
                                    <a href="${post.urlToPost}" title="" class="stretched-link btn btn-link">Читать</a>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            ${post.updated_at_human}
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-dots-fill" viewBox="0 0 16 16">
                                                <path d="M16 8c0 3.866-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7M5 8a1 1 0 1 0-2 0 1 1 0 0 0 2 0m4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2"></path>
                                            </svg>
                                        </small>
                                    </p>
                                </div>
                            </div>
                        `;
                            document.querySelector('.posts-wall').appendChild(postDiv);
                            lastPostId = post.id;
                        });

                    }
                })
                .catch(error => console.log(error));
        }
    };

    let scrollTimeout;

    window.addEventListener('scroll', () => {
        if (!scrollTimeout) {
            scrollTimeout = setTimeout(() => {
                handleScroll();
                scrollTimeout = null;
            }, 2000);
        }
    });

    @endif
</script>
@endpush
