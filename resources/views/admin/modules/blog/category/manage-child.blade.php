<ul class="nested-list children" data-parent-id="{{$category->id}}">
    @foreach($childs as $child)
        <li class="nested-list__item" data-move-id="{{$child->id}}" data-sort="{{$child->sort_order}}">
            <div class="caret">
                <b>#{{ $child->id }}</b> &nbsp; {{ $child->title }}
                <div class="caret-actions">
                    <a href="{{ route('admin.blog.category.edit', ['category' => $child->id]) }}" class="form-btn form-edit">
                        <i class="far fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.blog.category.destroy', $child) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="form-submit" type="submit" onclick="return confirm('Are you sure?')">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                    <i class="fas fa-expand-arrows-alt"></i>
                </div>
            </div>
            @if($child->childs && count($child->childs))
                @include('admin.modules.blog.category.manage-child',['childs' => $child->childs])
            @else
                <ul class="nested-list" data-parent-id="{{$child->id}}">
                    <!-- Empty nested list to allow dropping items here -->
                </ul>
            @endif
        </li>
    @endforeach
</ul>
