@extends('layouts.app')
@section('header-navbar')
    @include('modules.blog.header-navbar')
@endsection
@push('style')
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
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    @foreach($posts as $post)
            <div class="col-sm-6">
                <div class="card">
                    <!-- Card img -->
                    <div class="position-relative">
                        <img class="card-img" src="assets/images/blog/4by3/01.jpg" alt="Card image">
                        <div class="card-img-overlay d-flex align-items-start flex-column p-3">
                            <!-- Card overlay bottom -->
                            <div class="w-100 mt-auto">
                                <!-- Card category -->
                                <a href="#" class="badge text-bg-warning mb-2"><i class="fas fa-circle me-2 small fw-bold"></i>Technology</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-3 info-block">
                        <!-- Sponsored Post -->
                        <a href="#!" class="mb-0 text-body small" tabindex="0" role="button" data-bs-container="body" data-bs-toggle="popover" data-bs-trigger="focus" data-bs-placement="top" data-bs-content="You're seeing this ad because your activity meets the intended audience of our site.">
                            <i class="bi bi-info-circle ps-1"></i> Sponsored
                        </a>
                        <h4 class="card-title mt-2"><a href="post-single.html" class="btn-link text-reset fw-bold">{{ $post->title }}</a></h4>
                        <p class="card-text">{!! $post->content !!}</p>
                        <!-- Card info -->
                        <ul class="nav nav-divider align-items-center d-none d-sm-inline-block">
                            <li class="nav-item">
                                <div class="nav-link">
                                    <div class="d-flex align-items-center position-relative">
                                        <div class="avatar avatar-xs">
                                            <img class="avatar-img rounded-circle" src="assets/images/avatar/01.jpg" alt="avatar">
                                        </div>
                                        <span class="ms-3">by <a href="#" class="stretched-link text-reset btn-link">Samuel</a></span>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item">Jan 22, 2022</li>
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
