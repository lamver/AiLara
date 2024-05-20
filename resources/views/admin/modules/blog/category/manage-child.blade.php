<ul class="nested">
    @foreach($childs as $child)
        <li>
            <span class="caret"><b>#{{ $child->id }}</b> {{ $child->title }}</span>
            <a href="{{ route('admin.blog.category.edit', ['category' => $child->id]) }}" class="btn btn-default">{{ __('admin.Edit') }}</a>
            <form action="{{ route('admin.blog.category.destroy', $child) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn btn-default" type="submit" onclick="return confirm('Are you sure?')">{{ __('admin.Delete') }}</button>
            </form>
            @if(count($child->childs))
                @include('admin.modules.blog.category.manage-child',['childs' => $child->childs])
            @endif
        </li>
    @endforeach
</ul>
