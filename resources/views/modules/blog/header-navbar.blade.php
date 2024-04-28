<nav class="navbar navbar-expand-lg" style="border-bottom: 1px solid lightslategray">
    <div class="container container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPage" aria-controls="navbarPage" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarPage">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @php $categoryTree = \App\Models\Modules\Blog\Category::getCategoryTree(); @endphp
                @foreach($categoryTree['categories'] as $category)
                    @if(count($category->childs))
                        <li class="nav-item dropdown">
                            @php
                                $categoryUri = '/' . \App\Models\Modules\Blog\Category::getCategoryUrlById($category->id);
                            @endphp
                            @if($categoryUri == request()->getRequestUri())
                            <a class="nav-link dropdown-toggle" title="{{ $category->seo_description }}" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <b>{{ $category->title }}</b>
                            </a>
                            @else
                                <a class="nav-link dropdown-toggle" title="{{ $category->seo_description }}" href="{{ $categoryUri }}" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ $category->title }}
                                </a>
                            @endif
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @include('modules.blog.subcat-header-navbar',['childs' => $category->childs])
                            </ul>
                        </li>
                    @else
                        <li>
                            @php
                                $categoryUri = '/' . \App\Models\Modules\Blog\Category::getCategoryUrlById($category->id);
                            @endphp

                            @if($categoryUri == request()->getRequestUri())
                            <a title="{{ $category->seo_description }}" href="#" class="nav-link px-2 link-secondary disabled">
                                <b>{{ $category->title }}</b>
                            </a>
                            @else
                                <a title="{{ $category->seo_description }}" href="/{{ \App\Models\Modules\Blog\Category::getCategoryUrlById($category->id) }}" class="nav-link px-2 link-secondary">{{ $category->title }}</a>
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</nav>
