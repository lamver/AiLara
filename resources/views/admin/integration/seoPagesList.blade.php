@php $layout = 'layouts.admin'; @endphp
@extends($layout)
@section('content')
    <table class="table">
        <thead>
            <tr>
                <th>
                    uri
                </th>
                <th>
                    meta_title
                </th>
                <th>

                </th>
            </tr>
        </thead>
        @foreach($allPages as $pageData)
        <tr>
            <td>
                {{ $pageData->uri }}
            </td>
            <td>
                {{ $pageData->meta_title }}
            </td>
            <td>
                <a href="{{ route('admin.ais.page.edit', ['id' => $pageData->id]) }}">редактировать</a>
            </td>
        </tr>
        @endforeach
    </table>
@endsection
