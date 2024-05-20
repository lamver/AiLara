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
            <div class="col-md-8 post_block">
                <h1>{!! \App\Helpers\StrMaster::htmlTagClear($post->title) !!}</h1>
                <img style="max-width: 100%" class="lazy-load-image" alt="{{ $post->seo_title }}" data-src="{!! $post->image !!}" src="{{ \App\Helpers\ImageMaster::getRandomSprite() }}"/>
                {!! \App\Helpers\StrMaster::applyHtml($post->content) !!}
            </div>
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

    <div class="container">
        @if(!$post->denied_comments)
        <div class="mb-3">
            <livewire:Comments.comment-form :post="$post"/>
        </div>
        @endif
        @if(!$post->hide_existed_comments)
        <livewire:Comments.comment-list :post="$post"/>
        @endif
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
@endpush
