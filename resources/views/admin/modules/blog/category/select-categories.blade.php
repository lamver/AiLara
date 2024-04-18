@foreach($childs as $child)
    @if($value == $child->id)
        @continue
    @endif
    <option @if(isset($value) && $value == $child->parent_id) selected @endif value="{{$child->parent_id}}">{!!   str_repeat('&nbsp;', $loop->depth * 1) !!} {{$child->id}} {{$child->title}} / {{ $child->parent_id }}{{ $value }}</option>
    @if(count($child->childs))
        @include('admin.modules.blog.category.select-categories', ['childs' => $child->childs])
    @endif
@endforeach
