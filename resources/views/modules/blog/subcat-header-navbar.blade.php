@foreach($childs as $child)
<li><a class="dropdown-item" href="/{{ \App\Models\Modules\Blog\Category::getCategoryUrlById($child->id) }}">{{ $child->title }}</a></li>
@endforeach
