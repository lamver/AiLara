@foreach($childs as $child)
    @if(isset($model) && $model->id == $child->id)
        @continue
    @endif
    <option @if(isset($model) && $model->parent_id == $child->id) selected @endif value="{{$child->id}}">
        {!! str_repeat('&nbsp;', $loop->depth * 1) !!} {{$child->id}} {{$child->title}}
    </option>
    @if(count($child->childs))
        @include('admin.modules.blog.category.select-categories', ['childs' => $child->childs])
    @endif
@endforeach
