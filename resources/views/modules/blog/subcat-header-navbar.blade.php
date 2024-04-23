@foreach($childs as $child)
<li>
    <a class="dropdown-item"  title="{{ $child->seo_description }}" href="/{{ \App\Models\Modules\Blog\Category::getCategoryUrlById($child->id) }}">{{ $child->title }}</a>
</li>
@endforeach
