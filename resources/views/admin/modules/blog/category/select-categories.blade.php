@foreach($childs as $child)
    <option @if($value ==  $child->id) selected @endif value="{{$child->id}}">{{ $value }}{!!   str_repeat('&nbsp;', $loop->depth * 1) !!} {{$child->id}} {{$child->title}}</option>
    @if(count($child->childs))
        @include('admin.modules.blog.category.select-categories', ['childs' => $child->childs])
    @endif
@endforeach
