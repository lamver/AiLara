@foreach($posts as $post)
    <div class="card">
        <img src="{{ \App\Helpers\ImageMaster::getRandomSprite() }}"
             data-src="{{ \App\Helpers\ImageMaster::resizeImgFromCdn($post->image, 300, 300) }}"
             class="card-img-top lazy-load-image" alt="{{ $post->seo_title }}">
        <div class="card-body">
            <h2 style="font-size: 18px"
                class="card-title">{{ \App\Helpers\StrMaster::htmlTagClear($post->title) }}</h2>
            <p class="card-text">{{ \App\Helpers\StrMaster::htmlTagClear($post->content) }}</p>
            <a href="{{ $post->urlToPost }}" title="{{ $post->seo_title }}" class="stretched-link btn btn-link">Читать</a>
            <p class="card-text">
                <small class="text-muted">
                    {{ \Illuminate\Support\Carbon::create($post->updated_at)->shortRelativeDiffForHumans(date("Y-m-d h:i:s", time())) }}
                    &nbsp;
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-chat-dots-fill" viewBox="0 0 16 16">
                        <path
                            d="M16 8c0 3.866-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7M5 8a1 1 0 1 0-2 0 1 1 0 0 0 2 0m4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                    </svg>
                </small>
            </p>
        </div>
    </div>
@endforeach
