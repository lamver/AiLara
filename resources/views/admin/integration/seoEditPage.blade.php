@php $layout = 'layouts.admin'; @endphp
@extends($layout)
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.2/dist/quill.snow.css" rel="stylesheet" />
@endpush
@section('content')

    <h1>{{ $existingPage->uri }}</h1>
    <form method="post" class="form-control" action="{{ route('admin.Ais page save', ['id' => $existingPage->id]) }}">
        @csrf
        <label for="exampleInputEmail1" class="form-label">{{ __('admin.Meta title') }}</label>
        <div class="input-group">
            <input type="text" name="meta_title" value="{{ $existingPage->meta_title }}" class="form-control" id="metaTitle" aria-describedby="emailHelp">
            <span data-type-id="metaTitle" class="input-group-text" data-bs-toggle="modal"
                  data-bs-target="#aiModal"> &nbsp;<i class="fa fa-child"></i>&nbsp; ai
            </span>
        </div>
        <div id="emailHelp" class="form-text mb-3"></div>

        <label for="exampleInputEmail1" class="form-label">{{ __('admin.Meta description') }}</label>
        <div class="input-group ">
            <textarea class="form-control" name="meta_description" id="metaDescription">{{ $existingPage->meta_description }}</textarea>
            <span data-type-id="metaDescription" class="input-group-text" data-bs-toggle="modal"
                  data-bs-target="#aiModal"> &nbsp;<i class="fa fa-child"></i>&nbsp; ai
            </span>
        </div>
        <div id="emailHelp" class="form-text mb-3"></div>

        <label for="exampleInputEmail1" class="form-label">{{ __('admin.Meta keywords') }}</label>
        <div class="input-group">
            <input type="text" name="meta_keywords" value="{{ $existingPage->meta_keywords }}" class="form-control" id="metaKeywords" aria-describedby="emailHelp">
            <span data-type-id="metaKeywords" class="input-group-text" data-bs-toggle="modal"
                  data-bs-target="#aiModal"> &nbsp;<i class="fa fa-child"></i>&nbsp; ai
            </span>
        </div>
        <div id="emailHelp" class="form-text mb-3"></div>

        <label for="exampleInputEmail1" class="form-label">{{ __('admin.Meta image path') }}</label>
        <div class="input-group">
            <input type="text" name="meta_image_path" value="{{ $existingPage->meta_image_path }}" class="form-control" id="metaImagePath" aria-describedby="emailHelp">
            <span data-type-id="metaImagePath" class="input-group-text" data-bs-toggle="modal"
                  data-bs-target="#aiModal"> &nbsp;<i class="fa fa-child"></i>&nbsp; ai
            </span>
        </div>
        <div id="emailHelp" class="form-text mb-3"></div>

        <label for="exampleInputEmail1" class="form-label">{{ __('admin.Preview title') }}</label>
        <div class="input-group">
            <input type="text" name="preview_title" value="{{ $existingPage->preview_title }}" class="form-control" id="previewTitle" aria-describedby="emailHelp">
            <span data-type-id="previewTitle" class="input-group-text" data-bs-toggle="modal"
                  data-bs-target="#aiModal"> &nbsp;<i class="fa fa-child"></i>&nbsp; ai
            </span>
        </div>
        <div id="emailHelp" class="form-text mb-3"></div>

        <label for="exampleInputEmail1" class="form-label">{{ __('admin.Preview description') }}</label>
        <div class="input-group">
            <textarea class="form-control" name="preview_description" id="previewDescription">{{ $existingPage->preview_description }}</textarea>
            <span data-type-id="previewDescription" class="input-group-text" data-bs-toggle="modal"
                  data-bs-target="#aiModal"> &nbsp;<i class="fa fa-child"></i>&nbsp; ai
            </span>
        </div>
        <div id="emailHelp" class="form-text mb-3"></div>

        <label for="exampleInputEmail1" class="form-label">{{ __('admin.Preview image path') }}</label>
        <div class="input-group">
            <input type="text" name="preview_image_path" value="{{ $existingPage->preview_image_path }}" class="form-control" id="previewImagePath" aria-describedby="emailHelp">
            <span data-type-id="previewImagePath" class="input-group-text" data-bs-toggle="modal"
                  data-bs-target="#aiModal"> &nbsp;<i class="fa fa-child"></i>&nbsp; ai
            </span>
        </div>
        <div id="emailHelp" class="form-text mb-3"></div>

        <label for="exampleInputEmail1" class="form-label">{{ __('admin.Preview icon svg code') }}</label>
        <div class="input-group">
            <input type="text" name="preview_icon_svg_code" value="{{ $existingPage->preview_icon_svg_code }}" class="form-control" id="previewIconSvgCode" aria-describedby="emailHelp">
            <span data-type-id="previewIconSvgCode" class="input-group-text" data-bs-toggle="modal"
                  data-bs-target="#aiModal"> &nbsp;<i class="fa fa-child"></i>&nbsp; ai
            </span>
        </div>
        <div id="emailHelp" class="form-text mb-3"></div>

        <label for="exampleInputEmail1" class="form-label">{{ __('admin.Seo title') }}</label>
        <div class="input-group">
            <input type="text" name="seo_title" value="{{ $existingPage->seo_title }}" class="form-control" id="seoTitle" aria-describedby="emailHelp">
            <span data-type-id="seoTitle" class="input-group-text" data-bs-toggle="modal"
                  data-bs-target="#aiModal"> &nbsp;<i class="fa fa-child"></i>&nbsp; ai
            </span>
        </div>
        <div id="emailHelp" class="form-text mb-3"></div>

        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">{{ __('admin.Seo description') }}</label>
            <textarea class="form-control" style="display: none"  id="seo_description" name="seo_description">{{ $existingPage->seo_description }}</textarea>

            <div id="editor_seo_description" style="font-size: 16px;">
                {!! $existingPage->seo_description !!}
            </div>

            <div id="emailHelp" class="form-text"></div>
        </div>


        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">{{ __('admin.Seo content page') }}</label>
            <textarea style="display: none" class="form-control" rows="25" id="seo_content_page" name="seo_content_page">{{ $existingPage->seo_content_page }}</textarea>

            <div id="editor_seo_content_page" style="font-size: 16px;">
                {!! $existingPage->seo_content_page !!}
            </div>

            <div id="emailHelp" class="form-text"></div>
        </div>
        <button onclick="fillData()" type="submit" class="btn btn-primary">{{ __('admin.Save') }}</button>
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

        const quill = new Quill('#editor_seo_content_page', {
            modules: {
                toolbar: toolbarOptions
            },
            theme: 'snow'
        });

        const quill2 = new Quill('#editor_seo_description', {
            modules: {
                toolbar: toolbarOptions
            },
            theme: 'snow'
        });

        function fillData() {
            let parentBlock = document.getElementById('editor_seo_description');
            let editor_seo_description = parentBlock.querySelector('.ql-editor').innerHTML;

            document.getElementById('seo_description').innerHTML = editor_seo_description;
            parentBlock = document.getElementById('editor_seo_content_page');
            let seo_content_page = parentBlock.querySelector('.ql-editor').innerHTML;
            document.getElementById('seo_content_page').innerHTML = seo_content_page;

        }
    </script>

@endpush
