@extends('layouts.app')
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
            height: 100%;
            width: 100%;
            bottom: 0;
            left: 0;
            right: 0;
            background-image: -webkit-gradient(linear, left top, left bottom, from(transparent), to(black));
            background-image: linear-gradient(280deg, transparent, black);
            z-index: 0;
        }

        .post_card h2 a {
            color: white;
            text-decoration: none;
            max-width: 90%;
        }

        .post_card h2 {
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
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="px-2 py-3 my-5 text-center">
                    @if(!empty($aiForm->title_h1))
                    <h1 class="display-3 fw-bold">
                        {{ $aiForm->title_h1 }}
                    </h1>
                    @endif
                    @if(!empty($aiForm->title_h2))
                    <h2 class="display-6 fw-bold">
                        {{ $aiForm->title_h2 }}
                    </h2>
                    @endif

                    <div id="ai-form-{{ $aiForm->id }}">
                        <div class="spinner-border m-5" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(!empty($aiForm->content_on_page))
                {!! $aiForm->content_on_page !!}
        @endif

        @if($settings->home_page_view_forms_page && !empty($settings->home_page_view_forms_ids) && !empty(json_decode($settings->home_page_view_forms_ids, true)))
            @php
                $aiForms = \App\Models\Modules\AiForm\AiForm::query()->whereRaw('id IN ('.implode(',', json_decode($settings->home_page_view_forms_ids, true)).')')->get();
            @endphp
            @foreach($aiForms as $form)
                {{ $form->name }}
            @endforeach
        @endif
        <div style="clear: both"></div>
        <div class="row post_card">
            @if($settings->home_page_view_posts && !empty($settings->home_page_category_ids) && !empty(json_decode($settings->home_page_category_ids, true)))
                @php
                    $posts = \App\Models\Modules\Blog\Posts::getPostsByCategoryId(json_decode($settings->home_page_category_ids, true), 12);
                @endphp

                @foreach($posts as $post)
                    {{--{{ $post->title }} <br>--}}

                    <div class="col-md-4 top-four-post">
                        <div class="shadow-sm rounded info-block-top lazy-load-image" data-bg-url="{{ \App\Helpers\ImageMaster::resizeImgFromCdn($post->image, 800, 900) }}" style="height: 250px; background-image: url('{{ \App\Helpers\ImageMaster::getRandomSprite() }}'); background-size: 100%;">
                            <h2 class="text-overlay display-4 fw-bold" style="font-size: 1.2em; bottom: 50%">
                                <a class="stretched-link" href="{{ $post->urlToPost }}">{{ \App\Helpers\StrMaster::htmlTagClear($post->title, 50) }}</a>
                            </h2>
                            <div class="text-overlay" style="position: absolute; bottom: 10%; left: 5%; color: white; font-size: 0.8em;">
                                {{ \App\Helpers\StrMaster::htmlTagClear($post->content, 70) }}
                                <p></p>
                                <div class="text-muted" style="font-size: 1.0em;">
                                    <img width="30" height="30" class="rounded-circle" src="{{ \App\Models\User::getAvatarUrl($post->user) }}"/> &nbsp;
                                    {{ $post->user->name }}
                                    {{ \Illuminate\Support\Carbon::create($post->updated_at)->shortRelativeDiffForHumans(date("Y-m-d h:i:s", time())) }}
                                    <a title="{{ $post->category->seo_title }}" href="{{ \App\Models\Modules\Blog\Category::getCategoryUrlById($post->post_category_id) }}">
                                        {{ $post->category->title }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach
               {{--{{ json_encode($posts) }}--}}
            @endif
        </div>
    </div>
@endsection
@push('bottom-scripts')
    <script>
        const formId = {{ $aiForm->id }};
        const aiFormContainer = 'ai-form-container';
        const mainFormTemplate = document.getElementById("ai-form-" + formId);

        fetch(`/api/v1/form/template?id=`+formId+`&state=${Math.floor(Math.random() * 10000)}.${Date.now()}`)
            .then(response => response.text())
            .then(html => {
                mainFormTemplate.innerHTML = html;
                const scriptMainFormClient = document.createElement("script");
                scriptMainFormClient.type = "application/javascript";
                scriptMainFormClient.async = true;
                scriptMainFormClient.src = `/api/v1/form/js?id=`+formId+`&state=${Math.floor(Math.random() * 10000)}.${Date.now()}`;
                document.body.appendChild(scriptMainFormClient);
            }).catch(error => console.log(error));

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
