@extends('layouts.admin')
@section('content')
    <form method="post" action="{{route('admin.backup.makeBackup')}}" id="makeBackup">
        @csrf
        <button type="submit" class="btn btn-primary mb-5">
            <span>{{ __('admin.Make new backup') }}</span>
            <svg style="fill: rgb(255, 255, 255); display: none" width="24" height="24" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                <circle cx="4" cy="12" r="3">
                    <animate id="spinner_qFRN" begin="0;spinner_OcgL.end+0.25s" attributeName="cy" calcMode="spline"
                             dur="0.6s" values="12;6;12" keySplines=".33,.66,.66,1;.33,0,.66,.33"></animate>
                </circle>
                <circle cx="12" cy="12" r="3">
                    <animate begin="spinner_qFRN.begin+0.1s" attributeName="cy" calcMode="spline" dur="0.6s"
                             values="12;6;12" keySplines=".33,.66,.66,1;.33,0,.66,.33"></animate>
                </circle>
                <circle cx="20" cy="12" r="3">
                    <animate id="spinner_OcgL" begin="spinner_qFRN.begin+0.2s" attributeName="cy" calcMode="spline"
                             dur="0.6s" values="12;6;12" keySplines=".33,.66,.66,1;.33,0,.66,.33"></animate>
                </circle>
            </svg>
        </button>
    </form>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <span>{{$error}}</span>
                @endforeach
            </ul>
        </div>
    @endif
    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">{{__('admin.File')}}</th>
            <th scope="col">{{__('admin.Actions')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($backups as $backup)
            <tr>
                <th scope="row">{{ ++$loop->index }}</th>
                <td>{{$backup['name']}}</td>
                <td>
                    <div class="d-flex" role="group" aria-label="Basic example">
                        <a href="{{$backup['url']}}" class="btn btn-warning" title="{{__('login_as_user')}}" download>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                 class="bi bi-cloud-download" viewBox="0 0 16 16">
                                <path
                                    d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383"/>
                                <path
                                    d="M7.646 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V5.5a.5.5 0 0 0-1 0v8.793l-2.146-2.147a.5.5 0 0 0-.708.708z"/>
                            </svg>
                        </a>
                        &nbsp;
                        <form method="post" action="{{route('admin.backup.destroy')}}" id="formDelete">
                            @csrf
                            <input type="hidden" name="fileName" value="{{$backup['name']}}">
                            <button type="submit" class="btn btn-danger" title="{{__('delete')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                     class="bi bi-trash3" viewBox="0 0 16 16">
                                    <path
                                        d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@push('bottom-scripts')
    <script>
        (function () {
            // Form delete
            try {
                const formDelete = document.querySelector('form#formDelete');

                formDelete.addEventListener('submit', function (event) {
                    event.preventDefault();
                    if (confirm("{{__('delete?')}}")) {
                        this.submit();
                    }
                });

            } catch (e) {
                console.error(e)
            }

            // From makeBackup
            try {
                const makeBackup = document.querySelector('form#makeBackup');

                makeBackup.addEventListener('submit', function (event) {
                    this.querySelector('[type="submit"]').disabled = true
                    this.querySelector('[type="submit"] span').style.display = 'none'
                    this.querySelector('[type="submit"] svg').style.display = 'block'
                });

            } catch (e) {
                console.error(e)
            }

        })();
    </script>
@endpush

