@extends('layouts.admin')
@section('page_title')
    {{__('admin.Blog')}} / {{__('admin.Category')}}
@endsection
@section('page_options')
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.blog.category.create') }}" type="button" class="btn btn-sm btn-outline-success">{{ __('admin.Add') }}</a>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        /* Удалить пули по умолчанию */
        ul, #myUL {
            list-style-type: none;
        }

        /* Удалите поля и отступы из родительского ul */
        #myUL {
            margin: 0;
            padding: 0;
        }

        /* Стиль курсора/стрелки */
        .caret {
            cursor: pointer;
            user-select: none; /* Запретить выделение текста */
        }

        /* Создайте курсор/стрелку с юникодом, и стиль его */
        .caret::before {
            content: "\25B6";
            color: black;
            display: inline-block;
            margin-right: 6px;
        }

        /* Поверните значок курсора/стрелки при нажатии (с помощью JavaScript) */
        .caret-down::before {
            transform: rotate(90deg);
        }

        /* Скрыть вложенный список */
        .nested {
            display: block;
        }

        /* Показать вложенный список, когда пользователь нажимает на курсор стрелку (с JavaScript) */
        .active {
            display: block;
        }
    </style>
@endpush
@section('content')
    <div class="col-md-6">
        <ul id="myUL">
            @foreach($categories as $category)
                <li><span style="white-space: nowrap"  class="caret"><b>#{{ $category->id }}</b> {{ $category->title }}
                        <a href="{{ route('admin.blog.category.edit', ['category' => $category->id]) }}" class="btn btn-default">{{ __('admin.Edit') }}</a>
                        <form action="{{ route('admin.blog.category.destroy', $category) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-default" type="submit" onclick="return confirm('Are you sure?')">{{ __('admin.Delete') }}</button>
                        </form>
                    </span>
{{--                    <div class="btn-group">
                        <a href="#" class="btn btn-default active" aria-current="page">{{ $category->id }}</b> {{ $category->title }}</a>
                        <form action="{{ route('admin.blog.category.destroy', $category) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-default" type="submit">Delete</button>
                        </form>
                        <a href="{{ route('admin.blog.category.edit', ['category' => $category->id]) }}" class="btn btn-default">Edit</a>
                        <a href="#" class="btn btn-default">{{ $category->description }}</a>
                    </div>--}}
                    @if(count($category->childs))
                        @include('admin.modules.blog.category.manage-child',['childs' => $category->childs])
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endsection
