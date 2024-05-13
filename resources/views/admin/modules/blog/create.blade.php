@extends('layouts.admin')

@section('page_title')
    Blog / Posts / @if(isset($post)) Update @else Create @endif
@endsection
@section('page_options')
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.blog.post.index') }}" type="button" class="btn btn-sm btn-outline-success">All posts</a>
{{--            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>--}}
        </div>
{{--        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
            <span data-feather="calendar"></span>
            This week
        </button>--}}
    </div>
@endsection
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.2/dist/quill.snow.css" rel="stylesheet" />
    <style>
        [data-bs-toggle="modal" ] {
            cursor: pointer;
        }
        /**
        Генератор каринок
     */

        #imgOnDesResult {
            padding-top: 15px;
        }

        #innerResult img {
            width: -webkit-fill-available;
            max-width: 260px;
            cursor: pointer;
        }
        #innerResult label {
            position: relative;
            display: inline-block;
        }
        #innerResult label:before {
            content: '';
            position: absolute;
            border-radius: 50%;
            border: 3px white solid;
            top: 15px;
            left:15px;
            display:block;
            width: 25px;
            height: 25px;
            background: #34495e;
            cursor: pointer;
            opacity: 0.5;
        }
        #innerResult input:checked + label:before {
            background: #0d6efd;
            border: 3px white solid;
            opacity: 1;
        }

    </style>
@endpush
@section('content')
{{--{{ $post->title }}--}}
@php
    $route = route('admin.blog.post.store');
    $method = 'POST';
    $btnName = 'Create';
@endphp
@if(isset($post))
    @php
    $route = route('admin.blog.post.update', ['post' => $post->id]);
    $method = 'PUT';
    $btnName = 'Update';
    @endphp
@endif
<form method="post" action="{{ $route }}">
    <div class="row">
        <div class="col-md-10">
            <div class="form-floating mb-3">
                <input id="title" type="text" class="form-control " name="title" value="@if(isset($post)) {{ $post->title }} @endif" required="true" placeholder="">
                <label for="floatingInput">title</label>
            </div>
            <textarea style="display: none" name="content" id="content" class="form-control" rows="30">@if(isset($post)) {{ $post->content }} @endif</textarea>
            <div id="editor_content" style="font-size: 16px; height: 600px;">
                @if(isset($post)) {!! \App\Helpers\StrMaster::applyHtml($post->content) !!} @endif
            </div>
            <p></p>
            <div class="form-floating mb-3">
                <input id="title" type="text" class="form-control " name="description" value="@if(isset($post)) {{ $post->description }} @endif" placeholder="">
                <label for="floatingInput">description</label>
            </div>
            <div class="form-floating mb-3">
                <input id="title" type="text" class="form-control " name="seo_title" value="@if(isset($post)) {{ $post->seo_title }} @endif" placeholder="">
                <label for="floatingInput">seo_title</label>
            </div>
            <div class="form-floating mb-3">
                <input id="title" type="text" class="form-control " name="seo_description" value="@if(isset($post)) {{ $post->seo_description }} @endif" placeholder="">
                <label for="floatingInput">seo_description</label>
            </div>
            <div class="form-floating mb-3">
                <input id="title" type="text" class="form-control " name="source_url" value="@if(isset($post)) {{ $post->source_url }} @endif" placeholder="">
                <label for="floatingInput">source_url</label>
                @if(isset($post))
                    <a class="btn-link btn" target="_blank" href="{{ $post->source_url }}">{{ __('admin.Go to source') }}</a>
                @endif
            </div>

            <div class="form-floating mb-3">
                <input id="title" type="text" class="form-control " name="image" value="@if(isset($post)) {{ $post->image }} @endif" placeholder="">
                <span data-type-id="label_image" data-type="image"
                      class="input-group-text"
                      data-bs-toggle="modal"
                      data-bs-target="#aiModal">
                    &nbsp;<i class="fa fa-child"></i>&nbsp; ai
                </span>
                <label for="floatingInput">image</label>
                @if(isset($post))
                    <p></p>
                <img width="100" height="100" src="{{ $post->image }}"/>
                @endif
            </div>
            <div class="form-floating mb-3">
                <input id="title" type="text" class="form-control " name="unique_id_after_import" value="@if(isset($post)) {{ $post->unique_id_after_import }} @endif" placeholder="">
                <label for="floatingInput">unique_id_after_import</label>
            </div>
        </div>
        <div class="col-md-2">
            <label for="label_status">status</label>
            <select id="label_status" name="status" class="form form-control">
                @foreach(\App\Models\Modules\Blog\Posts::STATUS as $postStatus)
                    <option @if(isset($post) && $post->status ==  $postStatus) selected @endif value="{{ $postStatus }}">{{ $postStatus }}</option>
                @endforeach
            </select>
            <label for="label_post_category_id">post_category_id</label>
            <select id="label_post_category_id" name="post_category_id" class="form form-control">
                <option value=""> - no parent cat -</option>
                @foreach($categoryTree['categories'] as $category)
                    <option @if(isset($post) && $post->post_category_id ==  $category->id) selected @endif value="{{$category->id}}">{{$category->id}} {{$category->title}}</option>
                    @if(count($category->childs)) {{--{{ print_r($category->childs[0]->title) }}--}}
                    @include('admin.modules.blog.category.select-categories', ['childs' => $category->childs, 'model' => isset($post) ? $post : null])
                    @endif
                @endforeach
            </select>
            <label for="label_status">author_id</label>
            <select id="label_status" name="author_id" class="form form-control">
                @foreach(\App\Models\User::all() as $users)
                    <option @if(isset($post) && $post->author_id ==  $users->id) selected @endif value="{{ $users->id }}">{{ $users->id }} | {{ $users->name }}</option>
                @endforeach
            </select>
            <label for="label_status">denied_comments</label>
            <select id="label_status" name="denied_comments" class="form form-control">
                <option @if(isset($post) && $post->denied_comments ==  false) selected @endif value="0">{{ __('admin.No') }}</option>
                <option @if(isset($post) && $post->denied_comments ==  true) selected @endif value="1">{{ __('admin.Yes') }}</option>
            </select>
            <label for="label_status">hide_existed_comments</label>
            <select id="label_status" name="hide_existed_comments" class="form form-control">
                <option @if(isset($post) && $post->hide_existed_comments ==  false) selected @endif value="0">{{ __('admin.No') }}</option>
                <option @if(isset($post) && $post->hide_existed_comments ==  true) selected @endif value="1">{{ __('admin.Yes') }}</option>
            </select>
            <label for="label_created_at">created_at</label>
            <input type="date" class="form-control" id="label_created_at" name="created_at" value="{{ $post->created_at ?? ''  }}" placeholder="date time">
            <label for="label_created_at">updated_at</label>
            <input type="date" class="form-control" id="label_created_at" name="created_at" value="{{ $post->updated_at ?? ''  }}" placeholder="date time">

        </div>
    </div>
    @method($method)
    @csrf
    @foreach($modelParams as $param)
        {{--{{ $param->Type }} <br>--}}
        @if($param->Extra == 'auto_increment')
            @continue
        @endif

    @if(in_array($param->Field, [
    'status',
    'post_category_id',
    'author_id',
    'denied_comments',
    'hide_existed_comments',
    'title',
    'content',
    'updated_at',
    'created_at',
    'seo_description',
    'seo_title',
    'description',
    'source_url',
    'image',
    'unique_id_after_import',
    ]))
        @continue
    @endif
{{--        @if($param->Field == 'status')
            <label for="label_{{ $param->Field }}">{{ $param->Field }}</label>
            <select id="label_{{ $param->Field }}" name="{{ $param->Field }}" class="form form-control">
                @foreach(\App\Models\Modules\Blog\Posts::STATUS as $postStatus)
                    <option @if(isset($post) && $post->{$param->Field} ==  $postStatus) selected @endif value="{{ $postStatus }}">{{ $postStatus }}</option>
                @endforeach
            </select>
            @continue
        @endif--}}
{{--        @if($param->Field == 'post_category_id')
            <label for="label_{{ $param->Field }}">{{ $param->Field }}</label>
            <select id="label_{{ $param->Field }}" name="{{ $param->Field }}" class="form form-control">
                <option value=""> - no parent cat -</option>
                @foreach($categoryTree['categories'] as $category)
                    <option @if(isset($post) && $post->{$param->Field} ==  $category->id) selected @endif value="{{$category->id}}">{{$category->id}} {{$category->title}}</option>
                    @if(count($category->childs)) --}}{{--{{ print_r($category->childs[0]->title) }}--}}{{--
                    @include('admin.modules.blog.category.select-categories', ['childs' => $category->childs, 'model' => isset($post) ? $post : null])
                    @endif
                @endforeach
            </select>
            @continue
        @endif--}}
        @if(in_array($param->Type, ['bigint unsigned', 'int unsigned']))
            <label for="label_{{ $param->Field }}">{{ $param->Field }}</label>
            <input type="text" class="form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}" value="{{ $post->{$param->Field} ?? '' }}" aria-describedby="emailHelp" placeholder="{{ $param->Comment}}">
            <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
            @continue
        @endif
        @if(in_array($param->Type, ['varchar(255)']))
            <label for="label_{{ $param->Field }}">{{ $param->Field }}</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}"
                       value="{{ $post->{$param->Field} ?? ''  }}" aria-describedby="emailHelp"
                       placeholder="{{ $param->Comment}}">
                <span data-type-id="label_{{ $param->Field }}" data-type="{{ $param->Field }}"
                      class="input-group-text"
                      data-bs-toggle="modal"
                      data-bs-target="#aiModal">
                    &nbsp;<i class="fa fa-child"></i>&nbsp; ai
                </span>
            </div>
            <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
            @continue
        @endif
        @if(in_array($param->Type, ['text', 'longtext']))
            <label for="label_{{ $param->Field }}">{{ $param->Field }}</label>
            <div class="input-group mb-3">
                <textarea class="form form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}"
                          placeholder="{{ $param->Comment}}">{{ $post->{$param->Field}  ?? '' }}</textarea>
                <span data-type-id="label_{{ $param->Field }}" data-type="{{ $param->Field }}"
                      class="input-group-text"
                      data-bs-toggle="modal"
                      data-bs-target="#aiModal">
                        &nbsp;<i class="fa fa-child"></i>&nbsp; ai
                </span>
            </div>
            <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
            @continue
        @endif
        @if(in_array($param->Type, ['timestamp']))
            <label for="label_{{ $param->Field }}">{{ $param->Field }}</label>
            <input type="date" class="form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}" value="{{ $post->{$param->Field} ?? ''  }}" aria-describedby="emailHelp" placeholder="{{ $param->Comment}}">
            <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
            @continue
        @endif
        <br>{{ $param->Type }}<br>
        <label for="exampleInputEmail1">{{ $param->Field }}</label>
        <textarea class="form form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}" placeholder="{{ $param->Comment}}">{{ $post->{$param->Field}  ?? '' }}</textarea>
        <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
    @endforeach
    <br>
    <button class="btn btn-primary" type="submit">{{ $btnName }}</button>
</form>
@endsection
@push('bottom-scripts')

    <!-- Include the Quill library -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.2/dist/quill.js"></script>

    <!-- Initialize Quill editor -->
    <script>

        const toolbarOptions = [
            ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
            ['blockquote', 'code-block'],
            ['link', 'image', 'video', 'formula'],

            [{ 'header': 1 }, { 'header': 2 }],               // custom button values
            [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
            [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
            [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
            [{ 'direction': 'rtl' }],                         // text direction

            [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

            [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
            [{ 'font': [] }],
            [{ 'align': [] }],

            ['clean']                                         // remove formatting button
        ];

        const quill = new Quill('#editor_content', {
            modules: {
                toolbar: toolbarOptions
            },
            theme: 'snow'
        });

        quill.on('text-change', (delta, oldDelta, source) => {

            let parentBlock = document.getElementById('editor_content');
            let editor_seo_description = parentBlock.querySelector('.ql-editor').innerHTML;

            document.getElementById('content').innerHTML = editor_seo_description;
            /*if (source == 'api') {
                console.log('An API call triggered this change.');
            } else if (source == 'user') {
                console.log('A user action triggered this change.');
            }*/
        });

        function fillData() {
            let parentBlock = document.getElementById('editor_content');
            let editor_seo_description = parentBlock.querySelector('.ql-editor').innerHTML;

            document.getElementById('content').innerHTML = editor_seo_description;
    /*        parentBlock = document.getElementById('editor_seo_content_page');
            let seo_content_page = parentBlock.querySelector('.ql-editor').innerHTML;
            document.getElementById('seo_content_page').innerHTML = seo_content_page;*/

        }
    </script>

@endpush
