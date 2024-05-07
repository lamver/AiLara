<ul class="nested">
    @foreach($childs as $child)
        <li>
            <span class="caret">
                <input type="checkbox" name="category_ids[]" value="{{ $child->id }}" @if(in_array($child->id, $selected_ids)) checked @endif>
                <b>#{{ $child->id }}</b> {{ $child->title }}
            </span>
            @if(count($child->childs))
                @include('admin.modules.blog.category.manage-child',['childs' => $child->childs])
            @endif
        </li>
    @endforeach
</ul>
