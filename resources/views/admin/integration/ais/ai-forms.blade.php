@extends('layouts.admin')
@section('content')
<h2>Ai forms</h2>
    <a class="btn-link" href="{{ route('admin.ais.aiForms.newForm') }}">New form</a>
    <table class="table">
        @foreach($aiFormsConfig as $aiFormConfig)
        <tr>
            <td>
                <a href="{{ route('admin.ais.aiForms.formEdit', ['formId' => $aiFormConfig->id]) }}">
                    {{ $aiFormConfig->name }}
                </a>
            </td>
        </tr>
        @endforeach
    </table>
@endsection
