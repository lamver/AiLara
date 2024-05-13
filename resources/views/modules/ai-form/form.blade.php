@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="px-2 py-3 my-5 text-center">
                    @if(!empty($aiForm->title_h1))
                    <h1 class="display-5 fw-bold">
                        {{ $aiForm->title_h1 }}
                    </h1>
                    @endif
                    @if(!empty($aiForm->title_h2))
                    <h2>
                        {{ $aiForm->title_h2 }}
                    </h2>
                    @endif

                    <div id="ai-form-{{ $aiForm->id }}">
                        <div class="spinner-border m-5" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(!empty($aiForm->content_on_page))
                {!! $aiForm->content_on_page !!}
        @endif
    </div>
@endsection
@push('bottom-scripts')
    <script>
        const formId = {{ $aiForm->id }};
        const aiFormContainer = 'ai-form-container';
        const mainFormTemplate = document.getElementById("ai-form-" + formId);

        fetch(`/api/v1/form/template?id=`+formId+`&state=${Math.floor(Math.random() * 10000)}.${Date.now()}`)
            .then(response => response.text())
            .then(html => {
                mainFormTemplate.innerHTML = html;
                const scriptMainFormClient = document.createElement("script");
                scriptMainFormClient.type = "application/javascript";
                scriptMainFormClient.async = true;
                scriptMainFormClient.src = `/api/v1/form/js?id=`+formId+`&state=${Math.floor(Math.random() * 10000)}.${Date.now()}`;
                document.body.appendChild(scriptMainFormClient);
            }).catch(error => console.log(error));
    </script>
@endpush
