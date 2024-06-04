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
            width: 100%;
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
            <label for="label_title">{{ __('admin.Title') }}</label>
            <div class="input-group mb-3">
                <input id="label_title" type="text" class="form-control " name="title" value="@if(isset($post)) {{ $post->title }} @endif" required="true" placeholder="">
                <span data-type-id="label_title" class="input-group-text" data-bs-toggle="modal"
                      data-bs-target="#aiModal">&nbsp;<i class="fa fa-child"></i>&nbsp;ai</span>
            </div>
            <label for="content">{{ __('admin.Content') }}</label>
            <textarea style="display: none" name="content" id="content" class="form-control" rows="30">@if(isset($post)){{ $post->content }}@endif</textarea>
            <div id="editor_content" style="font-size: 16px; height: 600px;">
                @if(isset($post)){!! \App\Helpers\StrMaster::applyHtml($post->content) !!}@endif
            </div>
            <p></p>
            <label for="label_description">{{ __('admin.Description') }}</label>
            <div class="input-group mb-3">
                <input id="label_description" type="text" class="form-control " name="description" value="@if(isset($post)) {{ $post->description }} @endif" placeholder="">
                <span data-type-id="label_description" class="input-group-text" data-bs-toggle="modal"
                      data-bs-target="#aiModal">&nbsp;<i class="fa fa-child"></i>&nbsp;ai</span>
            </div>
                <label for="label_seo_title">{{ __('admin.Seo title') }}</label>
            <div class="input-group mb-3">
                <input id="label_seo_title" type="text" class="form-control " name="seo_title" value="@if(isset($post)) {{ $post->seo_title }} @endif" placeholder="">
                <span data-type-id="label_seo_title" class="input-group-text" data-bs-toggle="modal"
                      data-bs-target="#aiModal">&nbsp;<i class="fa fa-child"></i>&nbsp;ai</span>
            </div>
                <label for="label_seo_description">{{ __('admin.Seo description') }}</label>
            <div class="input-group mb-3">
                <input id="label_seo_description" type="text" class="form-control " name="seo_description" value="@if(isset($post)) {{ $post->seo_description }} @endif" placeholder="">
                <span data-type-id="label_seo_description" class="input-group-text" data-bs-toggle="modal"
                          data-bs-target="#aiModal">&nbsp;<i class="fa fa-child"></i>&nbsp;ai</span>
            </div>
            <div class="form-floating mb-3">
                <input id="title" type="text" class="form-control " name="source_url" value="@if(isset($post)) {{ $post->source_url }} @endif" placeholder="">
                <label for="floatingInput">{{ __('admin.Source url') }}</label>
                @if(isset($post) && isset($post->source_url))
                    <a class="btn-link btn" target="_blank" href="{{ $post->source_url }}">{{ __('admin.Go to source') }}</a>
                @endif
            </div>

            <label for="label_image">{{ __('admin.Image') }}</label>
            <div class="input-group mb-3">
                <input id="label_image" type="text" class="form-control " name="image" value="@if(isset($post)) {{ $post->image }} @endif" placeholder="">
                <span data-type-id="label_image" data-type="image"
                      class="input-group-text"
                      data-bs-toggle="modal"
                      data-bs-target="#aiModal">
                    &nbsp;<i class="fa fa-child"></i>&nbsp; ai
                </span>
                </div>
                @if( isset($post) && isset($post->image) )
                    <p></p>
                <img class="mb-3" width="100" height="100" src="{{ $post->image }}"/>
                @endif
                <div class="form-floating mb-3">
                <input id="title" type="text" class="form-control " name="unique_id_after_import" value="@if(isset($post)) {{ $post->unique_id_after_import }} @endif" placeholder="">
                <label for="floatingInput">{{ __('admin.Unique id after import') }}</label>
            </div>
        </div>
        <div class="col-md-2">
            <label for="label_status">{{ __('admin.Status') }}</label>
            <select id="label_status" name="status" class="form form-control">
                @foreach(\App\Models\Modules\Blog\Posts::STATUS as $postStatus)
                    <option @if(isset($post) && $post->status ==  $postStatus) selected @endif value="{{ $postStatus }}">{{ $postStatus }}</option>
                @endforeach
            </select>
            <label for="label_post_category_id">{{ __('admin.Post category id') }}</label>
            <select required id="label_post_category_id" name="post_category_id" class="form form-control">
                <option value="">{{ __('admin.Select category') }}</option>
                @foreach($categoryTree['categories'] as $category)
                    <option @if(isset($post) && $post->post_category_id ==  $category->id) selected @endif value="{{$category->id}}">{{$category->id}} {{$category->title}}</option>
                    @if(count($category->childs)) {{--{{ print_r($category->childs[0]->title) }}--}}
                    @include('admin.modules.blog.category.select-categories', ['childs' => $category->childs, 'model' => isset($post) ? $post : null])
                    @endif
                @endforeach
            </select>
            <label for="label_status">{{ __('admin.Author id') }}</label>
            <select id="label_status" name="author_id" class="form form-control">
                @foreach(\App\Models\User::all() as $users)
                    <option @if(isset($post) && $post->author_id ==  $users->id) selected @endif value="{{ $users->id }}">{{ $users->id }} | {{ $users->name }}</option>
                @endforeach
            </select>
            <label for="label_status">{{ __('admin.Denied comments') }}</label>
            <select id="label_status" name="denied_comments" class="form form-control">
                <option @if(isset($post) && $post->denied_comments ==  false) selected @endif value="0">{{ __('admin.No') }}</option>
                <option @if(isset($post) && $post->denied_comments ==  true) selected @endif value="1">{{ __('admin.Yes') }}</option>
            </select>
            <label for="label_status">{{ __('admin.Hide existed comments') }}</label>
            <select id="label_status" name="hide_existed_comments" class="form form-control">
                <option @if(isset($post) && $post->hide_existed_comments ==  false) selected @endif value="0">{{ __('admin.No') }}</option>
                <option @if(isset($post) && $post->hide_existed_comments ==  true) selected @endif value="1">{{ __('admin.Yes') }}</option>
            </select>
            <label for="label_created_at">{{ __('admin.Created at') }}</label>
            <input type="date" class="form-control" id="label_created_at" name="created_at" value="{{ $post->created_at ?? ''  }}" placeholder="date time">
            <label for="label_created_at">{{ __('admin.Updated at') }}</label>
            <input type="date" class="form-control" id="label_created_at" name="created_at" value="{{ $post->updated_at ?? ''  }}" placeholder="date time">
            <div class="mb-3">
                <label class="form-check-label" for="telegramBotId">{{ __('admin.Telegram bot') }}</label>
                <select class="form-select" id="telegramBotId" name="telegram_bot_id">
                    <option value="">{{__('admin.Empty')}}</option>
                    @foreach($telegramBots as $bot)
                        <option @if(isset($post) && $bot->id === $post->telegramBot?->id) selected @endif value="{{$bot->id}}">{{$bot->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-12 mt-3">

                <label for="label_status">{{__('admin.Telegram post url')}}</label>
                <select id="label_status" name="telegram_post_url" class="form form-control">
                    <option @if(isset($post) && $post->telegram_post_url ==  false) selected @endif value="0">{{ __('admin.No') }}</option>
                    <option @if(isset($post) && $post->telegram_post_url ==  true) selected @endif value="1">{{ __('admin.Yes') }}</option>
                </select>

            </div>

            <div class="mb-12 mt-3">
                <label class="form-label" style="font-size: 18px">  {{ __('admin.Telegram length_text') }}</label>
                <input class="form-control" name="telegram_length_text" value="@if(isset($post)) {{$post->telegram_length_text}} @endif" type="number"/>
            </div>

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
            'telegram_bot_id',
            'telegram_length_text',
            'telegram_post_url',
            ]))
                @continue
            @endif
            {{--        @if($param->Field == 'status')
                        <label for="label_{{ $param->Field }}">{{ __strTrans($param->Field, 'admin') }}</label>
                        <select id="label_{{ $param->Field }}" name="{{ $param->Field }}" class="form form-control">
                            @foreach(\App\Models\Modules\Blog\Posts::STATUS as $postStatus)
                                <option @if(isset($post) && $post->{$param->Field} ==  $postStatus) selected @endif value="{{ $postStatus }}">{{ $postStatus }}</option>
                            @endforeach
                        </select>
                        @continue
                    @endif--}}
            {{--        @if($param->Field == 'post_category_id')
                        <label for="label_{{ $param->Field }}">{{ __strTrans($param->Field, 'admin') }}</label>
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
                <label for="label_{{ $param->Field }}">{{ __strTrans($param->Field, 'admin') }}</label>
                <input type="text" class="form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}" value="{{ $post->{$param->Field} ?? '' }}" aria-describedby="emailHelp" placeholder="{{ $param->Comment}}">
                <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
                @continue
            @endif
            @if(in_array($param->Type, ['varchar(255)']))
                <label for="label_{{ $param->Field }}">{{ __strTrans($param->Field, 'admin') }}</label>
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
            <label for="label_{{ $param->Field }}">{{ __strTrans($param->Field, 'admin') }}</label>
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
            <label for="label_{{ $param->Field }}">{{ __strTrans($param->Field, 'admin') }}</label>
            <input type="date" class="form-control" id="label_{{ $param->Field }}" name="{{ $param->Field }}" value="{{ $post->{$param->Field} ?? ''  }}" aria-describedby="emailHelp" placeholder="{{ $param->Comment}}">
            <small id="emailHelp" class="form-text text-muted">{{ $param->Comment}}</small>
            @continue
        @endif
        <br>{{ $param->Type }}<br>
        <label for="exampleInputEmail1">{{ __strTrans($param->Field, 'admin') }}</label>
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
