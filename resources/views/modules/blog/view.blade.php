@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('index', $breadcrumbs))
@section('breadcrumbs-json-ld', Breadcrumbs::view('breadcrumbs::json-ld', 'index', $breadcrumbs))
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
                <img alt="{{ $post->seo_title }}" src="{!! $post->image !!}"/>
                {!! \App\Helpers\StrMaster::applyHtml($post->content) !!}
            </div>
        </div>
    </div>
@endsection
