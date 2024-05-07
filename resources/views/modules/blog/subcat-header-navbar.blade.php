@foreach($childs as $child)
<li>
    @php
        $categoryUri =  \App\Models\Modules\Blog\Category::getCategoryUrlById($child->id);
    @endphp
    @if($categoryUri == request()->getRequestUri())
        <a title="{{ $child->seo_description }}" href="#" class="nav-link px-2 link-secondary disabled">
            <b>{{ $child->title }}</b>
        </a>
    @else
        <a class="dropdown-item"  title="{{ $child->seo_description }}" href="{{ \App\Models\Modules\Blog\Category::getCategoryUrlById($child->id) }}">{{ $child->title }}</a>
    @endif
</li>
@endforeach
