<ul>
    @foreach($childs as $child)
        <li>
            <b>{{ $child->id }}</b> {{ $child->title }}<br>
            <form action="{{ route('admin.blog.category.destroy', $child) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn btn-link" type="submit">Delete</button>
            </form>
            <a href="{{ route('admin.blog.category.edit', ['category' => $child->id]) }}">Edit</a>
            <span class="text-muted">{{ $child->description }}</span>
            @if(count($child->childs))
                @include('admin.modules.blog.category.manage-child',['childs' => $child->childs])
            @endif
        </li>
    @endforeach
</ul>
