@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="px-2 py-3 my-5 text-center" id="response">
                    @if($task['result'] === true && $task['answer']['status'] !== 2)
                        <div class="card">
                            <div class="card-body"><h5 class="card-title">{{$task['answer']['prompt_full']}}</h5>
                                <p class="card-text">{{$task['answer']['answer']}}</p>
                                @else
                                    <div class="spinner-border m-5" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                </div>
            </div>
@endsection

@push('bottom-scripts')

<script>

    let fetchTast = () => {
        let id = {{$id}};
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
    };

    //TODO делать запрос каждые 10сек до 5 минут.
    if ({{$task['answer']['status']}} === 2) {
        fetchTast();
    }

</script>

@endpush
