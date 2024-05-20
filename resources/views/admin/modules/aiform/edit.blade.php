@extends('layouts.admin')
@push('top-scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsoneditor/10.0.1/jsoneditor.css" integrity="sha512-iOFdnlwX6UGb55bU5DL0tjWkS/+9jxRxw2KiRzyHMZARASUSwm0nEXBcdqsYni+t3UKJSK7vrwvlL8792/UMjQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('page_title')
    {{ __('admin.Ai forms') }} / {{ __('admin.edit') }}
@endsection
@section('page_options')
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.module.ai-form.index') }}" type="button" class="btn btn-sm btn-outline-success">{{ __('admin.Forms list') }}</a>
        </div>
    </div>
@endsection

@section('content')

    <form method="post" action="{{ empty($aiForm) ? route('admin.module.ai-form.store') : route('admin.module.ai-form.update', $aiForm) }}">
        @if(!empty($aiForm)) @method('PUT') @endif
        @csrf
        <div class="input-group mb-3">
            <input name="name" value="{{ $aiForm->name ?? '' }}" type="text" class="form-control" placeholder="" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-primary" type="submit" id="button-addon2">{{ __('admin.Save')}}</button>
        </div>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="form-config-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">
                  {{ __('admin.Form config') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="form-content-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">
                    {{ __('admin.Form content') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="form-settings-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">
                    {{ __('admin.Form settings') }}
                </button>
            </li>
        </ul>
        <div class="tab-content" id="formTabs">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="form-config-tab">
                <div id="jsoneditor" style="width: 100%; height: 800px;"></div>
                <div class="mb-3">
                    <label for="form_config" class="form-label">{{ __('admin.Config form') }}</label>
                    <textarea name="form_config" id="form_config" style="display: none" class="form-control" id="form_config" rows="10">{{ $aiForm->form_config ?? '' }}</textarea>
                </div>
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="form-content-tab">

                <div class="mb-3">
                    <label for="content_on_page" class="form-label">{{ __('admin.Content on page') }}</label>
                    <textarea name="content_on_page" class="form-control" id="content_on_page" rows="15">{{ $aiForm->content_on_page ?? '' }}</textarea>
                </div>

                <label for="formSeoTitle" class="form-label">{{ __('admin.Seo title') }}</label>
                <input name="seo_title" value="{{ $aiForm->seo_title ?? '' }}" type="text" class="form-control" id="formSeoTitle" aria-describedby="emailHelp">

                <div class="mb-3">
                    <label for="seo_description" class="form-label">{{ __('admin.Seo description') }}</label>
                    <textarea name="seo_description" class="form-control" id="seo_description" rows="7">{{ $aiForm->seo_description ?? '' }}</textarea>
                </div>

                <label for="image" class="form-label">{{ __('admin.Image') }}</label>
                <input name="image" value="{{ $aiForm->image ?? '' }}" type="text" class="form-control" id="image" aria-describedby="emailHelp">
                <div id="emailHelp" class="form-text">{{ __('admin.Name form ') }}</div>

                <div class="mb-3">
                    <label for="description_on_page" class="form-label">{{ __('admin.Description') }}</label>
                    <textarea name="description_on_page" class="form-control" id="description_on_page" rows="7">{{ $aiForm->description_on_page ?? '' }}</textarea>
                </div>

                <label for="title_h1" class="form-label">{{ __('admin.Title h1') }}</label>
                <input name="title_h1" value="{{ $aiForm->title_h1 ?? '' }}" type="text" class="form-control" id="title_h1" aria-describedby="emailHelp">

                <label for="title_h2" class="form-label">{{ __('admin.Title h2') }}</label>
                <input name="title_h2" value="{{ $aiForm->title_h2 ?? '' }}" type="text" class="form-control" id="title_h2" aria-describedby="emailHelp">

                <div class="mb-3">
                    <label for="posts_ids" class="form-label">{{ __('admin.Posts ids') }}</label>
                    <textarea name="posts_ids" class="form-control" id="posts_ids" rows="3">{{ $aiForm->posts_ids ?? '' }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">{{ __('admin.Slug') }}</label>
                    <input name="slug" value="{{ $aiForm->slug ?? ''}}" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>
            </div>
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="form-settings-tab">

                <label class="form-check-label" for="flexCheckDefault">
                    {{ __('admin.View posts') }}
                </label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="view_posts" value="1" id="flexRadioDefault1" @if(isset($aiForm) && $aiForm->view_posts) checked @endif>
                    <label class="form-check-label" for="flexRadioDefault1">
                      {{ __('admin.Yes') }}
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="view_posts" value="0" id="flexRadioDefault2" @if(isset($aiForm) && !$aiForm->view_posts) checked @endif>
                    <label class="form-check-label" for="flexRadioDefault2">
                        {{ __('admin.No') }}
                    </label>
                </div>

                @include('admin.modules.blog.category.for_selected.main', ['categories' => $categories, 'selected_ids' => !empty(isset($aiForm) && $aiForm->category_ids) ? json_decode($aiForm->category_ids) : [] ])

                <label class="form-check-label" for="flexCheckDefault">
                    {{ __('admin.Allow comments') }}
                </label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="allow_comments" value="1" id="flexRadioDefault1" @if(isset($aiForm) && $aiForm->allow_comments) checked @endif>
                    <label class="form-check-label" for="flexRadioDefault1">
                        {{ __('admin.Yes') }}
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="allow_comments" value="0" id="flexRadioDefault2" @if(isset($aiForm) && !$aiForm->allow_comments) checked @endif>
                    <label class="form-check-label" for="flexRadioDefault2">
                        {{ __('admin.No') }}
                    </label>
                </div>

                <label class="form-check-label" for="flexCheckDefault">
                    {{ __('admin.Allow indexing results') }}
                </label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="allow_indexing_results" value="1" id="flexRadioDefault1" @if(isset($aiForm) && $aiForm->allow_indexing_results) checked @endif>
                    <label class="form-check-label" for="flexRadioDefault1">
                        {{ __('admin.Yes') }}
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="allow_indexing_results" value="0" id="flexRadioDefault2" @if(isset($aiForm) && !$aiForm->allow_indexing_results) checked @endif>
                    <label class="form-check-label" for="flexRadioDefault2">
                        {{ __('admin.No') }}
                    </label>
                </div>

                <label for="price_per_symbol" class="form-label">{{ __('admin.Price per symbol') }}</label>
                <input name="price_per_symbol" value="{{ $aiForm->price_per_symbol ?? '' }}" type="number" step="0.01" class="form-control amount" id="price_per_symbol" aria-describedby="emailHelp">

                <label for="price_per_execute" class="form-label">{{ __('admin.Price per execute') }}</label>
                <input name="price_per_execute" value="{{ $aiForm->price_per_execute ?? '' }}" type="number" step="0.01" class="form-control amount" id="price_per_execute" aria-describedby="emailHelp">

            </div>
        </div>
            <br>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">{{ __('admin.Save')}}</button>
        </div>
    </form>
@endsection
@push('bottom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsoneditor/10.0.1/jsoneditor.min.js" integrity="sha512-bcBrdzrs/vzIDUvJLDTcWCYlHqoup9V6NTopRV1xRZYVIy+IoXu71spBq+TBHGKuEo76e6SIpfd02VqxNscEyw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // create the editor
        const container = document.getElementById("jsoneditor")
        const options = {
            mode: 'tree',
            expandAll: true,
            modes: ['code', 'form', 'text', 'tree', 'view', 'preview'], // allowed modes
            onModeChange: function (newMode, oldMode) {
                console.log('Mode switched from', oldMode, 'to', newMode);
            },
            onEvent: function(node, event) {
                getJSON();
                /*if (node.value !== undefined) {
                    console.log(event.type + ' event ' +
                        'on value ' + JSON.stringify(node.value) + ' ' +
                        'at path ' + JSON.stringify(node.path)
                    )
                } else {
                    console.log(event.type + ' event ' +
                        'on field ' + JSON.stringify(node.field) + ' ' +
                        'at path ' + JSON.stringify(node.path)
                    )
                }*/
            },
            onChangeJSON: function (json) {
                getJSON();
            }
        }
        const editor = new JSONEditor(container, options)

        // set json
        const initialJson = {!! $aiForm->form_config ?? json_encode(\App\Models\Modules\AiForm\AiForm::getFormConfig()) !!}
        editor.set(initialJson)

        // get json
        const updatedJson = editor.get()

        editor.expandAll();
        // get json
        function getJSON() {
            let json = editor.get();

            document.getElementById('form_config').innerText = JSON.stringify(json);
        }

        getJSON();

        document.querySelector('.amount').addEventListener('input', function(event) {
            let inputValue = event.target.value;

            // Заменяем все символы, кроме цифр, точки и запятой на пустую строку
            inputValue = inputValue.replace(/[^0-9.,]/g, '');

            // Заменяем все точки на запятые, если есть более 1 точки
            let pointIndex = inputValue.indexOf('.');
            if (pointIndex !== -1) {
                inputValue = inputValue.split('').map((char, index) => {
                    if (char === '.' && index !== pointIndex) {
                        return ',';
                    } else {
                        return char;
                    }
                }).join('');
            }

            event.target.value = inputValue;
        });
    </script>
@endpush
