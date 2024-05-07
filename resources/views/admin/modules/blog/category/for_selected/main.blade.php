<div class="col-md-6">
    <ul id="myUL">
        @foreach($categories as $category)
            <li>
                <span style="white-space: nowrap" class="caret">
                    <input type="checkbox" name="category_ids[]" @if(in_array($category->id, $selected_ids)) checked @endif value="{{ $category->id }}">
                    <b>#{{ $category->id }}</b>
                    {{ $category->title }}
                </span>
                @if(count($category->childs))
                    @include('admin.modules.blog.category.for_selected.child',['childs' => $category->childs, 'selected_ids' => $selected_ids])
                @endif
            </li>
        @endforeach
    </ul>
</div>
