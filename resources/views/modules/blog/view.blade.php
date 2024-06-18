@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('index', $breadcrumbs))
@section('breadcrumbs-json-ld', Breadcrumbs::view('breadcrumbs::json-ld', 'index', $breadcrumbs))
@section('top-sub-app-navbar')
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

        .post_block img {
            max-width: 100%;
            box-shadow: 0px 0px 10px -3px rgba(0,0,0,0.75);
            -webkit-box-shadow: 0px 0px 10px -3px rgba(0,0,0,0.75);
            -moz-box-shadow: 0px 0px 10px -3px rgba(0,0,0,0.75);
        }

    </style>
@endpush
@section('content')
    <div class="container">
        <div class="row">
            <div class=" @if($settingBlog->load_posts_on_post_page) col-md-8 @else col-md-14 @endif  post_block">
                <h1>{!! \App\Helpers\StrMaster::htmlTagClear($post->title) !!}</h1>
                <img style="max-width: 100%" class="lazy-load-image" alt="{{ $post->seo_title }}" data-src="{!! $post->image !!}" src="{{ \App\Helpers\ImageMaster::getRandomSprite() }}"/>
                {!! \App\Helpers\StrMaster::applyHtml($post->content) !!}

                @if (Auth::check())
                    @if(!$post->denied_comments)
                        <div class="mb-3">
                            <livewire:Comments.comment-form :post="$post"/>
                        </div>
                    @endif
                @else
                    <div class="mb-3">
                        {{__('To leave a comment you need to')}}
                        <a href="{{ route('login') }}">{{ __('Login') }}</a> /
                        <a href="{{ route('register') }}">{{ __('Create account') }}</a>
                    </div>
                @endif
                @if(!$post->hide_existed_comments)
                    <livewire:Comments.comment-list :post="$post"/>
                @endif
            </div>

            @if($settingBlog->load_posts_on_post_page)
                <div class="col-md-4 d-flex flex-column gap-3" id="autoLoad"></div>
            @endif
        </div>
        @auth('web')
        @if(\Illuminate\Support\Facades\Auth::user()->can('posts.edit'))
                <div class="btn-group me-2" role="group" aria-label="Second group">
                    <a href="?status=draft" type="button" class="btn btn-secondary">Fast to Draft</a>
                    <a href="{{ route('admin.blog.post.edit', $post) }}" type="button" class="btn btn-secondary">Edit</a>
                </div>
        @endif
        @endauth

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
    @if($settingBlog->load_posts_on_post_page)
        <script>
        let postsLoader = {
            HEADERS: {
                "Content-Type": "application/json",
                "Accept": "application/json",
            },
            postInfo: @json($post),
            categoryId: null,
            perPage: 2,
            pageCount: 1,
            isFetchingPage: false,
            nextPageUrl: "",
            currentView: '',
            initialize: function(perPage, currentView) {
                this.perPage = perPage;
                this.currentView = currentView;
                this.categoryId = this.postInfo.post_category_id;
                window.addEventListener('scroll', () => this.handleScrollEvent());
                this.fetchPostsResult();

            },
            fetchPostsPage: function() {
                let requestUrl = this.generatePostPageRequestUrl();
                return fetch(requestUrl, {headers: this.HEADERS}).then(response => response.json());
            },
            generatePostPageRequestUrl: function() {
                let basePostsUrl = '{{route('blog.post.getPosts')}}';
                basePostsUrl += `?page=${this.pageCount}&categoryId=${this.categoryId}&perPage=${this.perPage}`;
                basePostsUrl += `&currentView=${this.currentView}`;
                return basePostsUrl;
            },
            isInViewPort: function() {
                let postsContainer = document.getElementById('autoLoad');
                let rect = postsContainer.getBoundingClientRect();
                return rect.bottom <= (window.innerHeight || document.documentElement.clientHeight);
            },
            appendPosts: function(json) {
                console.log(json);
                this.nextPageUrl = json.posts.next_page_url;

                let postsContainer = document.getElementById('autoLoad');
                postsContainer.insertAdjacentHTML('beforeend', json.html);
            },
            handleScrollEvent: function() {
                if (this.isInViewPort() && !this.isFetchingPage && this.nextPageUrl !== null) {
                    this.isFetchingPage = true;
                    this.fetchPostsResult();
                }
            },
            fetchPostsResult: function () {
                this.fetchPostsPage().then(json => {
                    this.appendPosts(json);
                    this.pageCount++;
                    this.isFetchingPage = false;
                });
            }

        }

        // Count Pre load page
        let perPage = 2;
        // The blade view
        let bladeView = 'modules.blog.category_right_part';

        postsLoader.initialize(perPage, bladeView);

    </script>
    @endif
@endpush
