@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="px-2 py-3 my-5 text-center" id="response">
                    <div class="spinner-border m-5" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('bottom-scripts')
    <script>
        let id = location.pathname.split('/').pop();
        fetch(`/api/task/get_task?task_id=${id}`)
            .then(response => response.json())
            .then(json => {

                let responseHtml = document.querySelector('#response');

                if (json.result !== true) {
                    responseHtml.innerHTML = '<div class="alert alert-danger"> Something went wrong. <br> please refresh page </div>';
                }

                if (json.answer.status === 2) {
                    //TODO Сделать повторный запрос...
                    responseHtml.innerHTML = '<div class="alert alert-secondary">In process...</div>';
                }

                responseHtml.innerHTML = `<div class="card"> <div class="card-body"> <h5 class="card-title">${json.answer.prompt_full}</h5> <p class="card-text">${json.answer.answer} </p>`;


            }).catch(error => console.log(error));
    </script>

@endpush
