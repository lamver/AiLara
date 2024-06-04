@extends('layouts.admin')
@section('page_title')
    {{__('admin.Blog')}} / {{__('admin.Category')}}
@endsection
@section('page_options')
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.blog.category.create') }}" type="button" class="btn btn-sm btn-outline-success"><i class="fas fa-plus"></i> {{ __('admin.Add') }}</a>
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
           /* cursor: pointer;*/
            user-select: none; /* Запретить выделение текста */
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .caret .fa-expand-arrows-alt {
            font-size: 20px;
        }
        .caret-actions {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-left: auto;
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

        #myUL.nested-list,
        #myUL .nested-list {
            list-style-type: none;
            position: relative;
           /* background: #a0a7a3;*/
            padding: 0 0 27px 0;
        }

        .nested-list__item {
            margin: 5px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .children .nested-list__item {
            margin-left: 15px;
        }

        .nested-list__item--highlight {
            background-color: #e0e0e0;
        }

        .form-submit {
            border: none;
            background: transparent;
        }
        .form-edit {
            color: #33a904;
        }
        .form-submit i:hover {
            color: #9c1001;
        }
        .form-btn, .form-submit {
            font-size: 20px;
        }
        .form-submit i {
            color: #dd1805;
        }


    </style>
@endpush

@section('content')

    <div class="col-md-12">
        <ul class="nested-list" id="myUL" data-parent-id="null">
            @foreach($categories as $category)
                <li class="nested-list__item" data-move-id="{{$category->id}}" data-sort="{{$category->sort_order}}" >
                    <div style="white-space: nowrap"  class="caret"><b>#{{ $category->id }}</b> &nbsp; {{ $category->title }}
                        <div class="caret-actions">
                            <a href="{{ route('admin.blog.category.edit', ['category' => $category->id]) }}" class="form-btn form-edit">
                                <i class="far fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.blog.category.destroy', $category) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="form-submit" type="submit" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                            <i style="font-size: 20px" class="fas fa-expand-arrows-alt"></i>
                        </div>
                    </div>
                        @if($category->children && count($category->children))
                            @include('admin.modules.blog.category.manage-child',['childs' => $category->children])
                        @else
                        <ul class="nested-list" data-parent-id="{{$category->id}}">
                            <!-- Empty nested list to allow dropping items here -->
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endsection

@push('bottom-scripts')
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/html5sortable@0.14.0/dist/html5sortable.min.js"></script>
    <scritp>
        <script>
            // Initialize the sortable list
            function initSortable() {
                const lists = document.querySelectorAll('.nested-list');
                lists.forEach(list => {

                    let sort = sortable(list, {
                        connectWith: '.nested-list', // Allow dragging between nested lists
                        // items: '> .nested-list__item', // Make only direct children sortable
                        handle: '.fa-expand-arrows-alt',
                        hoverClass: 'is-hovered is-hovered-class',
                    });

                    sort[0].addEventListener('sortupdate', function(e) {
                        const dateSend = {};

                        let itemSort = [];
                        let items = e.detail.destination.items;

                        for (let index in items) {
                            itemSort.push({
                                id: items[index].dataset.moveId,
                                sortOrder: index,
                            });
                        }

                        dateSend.parentId = e.detail.destination.container.dataset.parentId;
                        dateSend.moveId = e.detail.item.dataset.moveId;
                        dateSend.sortOrder = itemSort;

                        console.log(dateSend);
                        fetchPost('{{route('admin.blog.category.sort')}}', dateSend);
                    });

                });
            }

            let fetchPost = async function (url, data) {

                let result = await fetch(url, {
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-Token": document.querySelector('input[name="_token"]').value
                    },
                    method: "post",
                    credentials: "same-origin",
                    body: JSON.stringify(data)
                });

                return await result.json();
            }

            // Call the function to initialize sortable lists
            initSortable();
        </script>
    </scritp>
@endpush

