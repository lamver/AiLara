@extends('layouts.app')
@section('stylesheet')

@endsection
@push('styles')
    <style>
        .info-block-top {
            background-position: center center;
            background-repeat: no-repeat;
            position: relative;
            margin-bottom: 10px;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.5);
        }

        h2 a {
            color: white;
            text-decoration: none;
            max-width: 90%;
        }

        h2 {
            bottom: 50%;
            left: 5%;
            position: absolute;
        }

        .text-overlay {
/*            background-color: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);*/
        }
    </style>
@endpush
@section('header-navbar')
    @include('modules.blog.header-navbar')
@endsection
@section('content')
    <div class="container">
        <div class="row">
            @if(isset($topFourPosts[0]) && !empty($topFourPosts[0]->title))
                <div class="col-md-6">
                    <div class="shadow-sm rounded info-block-top" style="height: 520px; background-image: url('{{ $topFourPosts[0]->image }}'); box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5); ">
                        <h2 class="text-overlay" style="position: absolute">
                            <a class="stretched-link" href="{{ $topFourPosts[0]->urlToPost }}">{{ $topFourPosts[0]->title }}</a>
                        </h2>
                        <div class="text-overlay" style="position: absolute; bottom: 20%; left: 5%; color: white; font-size: 1.8em;">
                            {{ \Illuminate\Support\Str::limit(strip_tags($topFourPosts[0]->content)) }}
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-md-6">
                <div class="row">
                    @if(isset($topFourPosts[1]) && !empty($topFourPosts[0]->title))
                        <div class="col-md-12 top-four-post">
                            <div class="shadow-sm rounded info-block-top" style="height: 250px; background-image: url('{{ $topFourPosts[1]->image }}')">
                                <h2 class="text-overlay" style="position: absolute; font-size: 1.6em">
                                    <a class="stretched-link" href="{{ $topFourPosts[1]->urlToPost }}">{{ $topFourPosts[1]->title }}</a>
                                </h2>
                                <div class="text-overlay" style="position: absolute; bottom: 20%; left: 5%; color: white; font-size: 1.2em;">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($topFourPosts[1]->content)) }}
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(isset($topFourPosts[2]) && !empty($topFourPosts[0]->title))
                        <div class="col-md-6 top-four-post">
                            <div class="shadow-sm rounded info-block-top" style="height: 250px; background-image: url('{{ $topFourPosts[2]->image }}')">
                                <h2 class="text-overlay" style="font-size: 1.2em">
                                    <a class="stretched-link" href="{{ $topFourPosts[2]->urlToPost }}">{{ $topFourPosts[2]->title }}</a>
                                </h2>
                                <div class="text-overlay" style="position: absolute; bottom: 20%; left: 5%; color: white; font-size: 0.8em;">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($topFourPosts[2]->content)) }}
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(isset($topFourPosts[3]) && !empty($topFourPosts[0]->title))
                        <div class="col-md-6 top-four-post">
                            <div class="shadow-sm rounded info-block-top" style="height: 250px; background-image: url('{{ $topFourPosts[3]->image }}')">
                                <h2 class="text-overlay" style="position: absolute; font-size: 1.2em">
                                    <a class="stretched-link" href="{{ $topFourPosts[3]->urlToPost }}">{{ $topFourPosts[3]->title }}</a>
                                </h2>
                                <div class="text-overlay" style="position: absolute; bottom: 20%; left: 5%; color: white; font-size: 0.8em;">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($topFourPosts[3]->content)) }}
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
                <div class="row">
                    @foreach($topPostsDifferentCategories as $post)
                        <div class="col-md-6">
                 {{--           <h4>{{ $post->title }}</h4>--}}
                            <div class="card mb-3">
                                @if(!empty($post->image))
                                <img src="{{ $post->image }}" class="card-img-top" alt="...">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $post->title }}</h5>
                                    <p class="card-text">{{ \Illuminate\Support\Str::limit(strip_tags($post->content)) }}</p>
                                    {{--{{ \App\Models\Modules\Blog\Posts::getUrlPostById($post->id) }}--}}
                                    <a href="{{ $post->urlToPost }}" class="stretched-link btn btn-link">Читать</a>
                                    <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
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
