@extends('layouts.admin')
@push('top-scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsoneditor/10.0.1/jsoneditor.css" integrity="sha512-iOFdnlwX6UGb55bU5DL0tjWkS/+9jxRxw2KiRzyHMZARASUSwm0nEXBcdqsYni+t3UKJSK7vrwvlL8792/UMjQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
    <h2>{{ __('admin.New form') }}</h2>
<!--    <a class="btn-link" href="">New form</a>-->
<hr>
    <form method="post" action="{{ route('admin.ais.aiForms.newFormCreate') }}">
        @csrf
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">{{ __('admin.Name form') }}</label>
            <input name="name" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text">{{ __('admin.Name form ') }}</div>
        </div>
        <div id="jsoneditor" style="width: 100%; height: 800px;"></div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">{{ __('admin.Config form') }}</label>
            <textarea id="form_config" style="display: none" name="form_config" class="form-control" id="exampleInputPassword1" rows="10">{{ $prototypeForm }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">{{ __('admin.Save')}}</button>
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
            }
        }
        const editor = new JSONEditor(container, options)

        // set json
        const initialJson = {!! $prototypeForm !!}
        editor.set(initialJson)

        // get json
        const updatedJson = editor.get()

        editor.expandAll();
        // get json
        function getJSON() {
            var json = editor.get();
            document.getElementById('form_config').innerText = JSON.stringify(json);
            //console.log(JSON.stringify(json, null, 2));
        }

        console.log('json', initialJson)
        console.log('string', JSON.stringify(initialJson))
    </script>
@endpush
