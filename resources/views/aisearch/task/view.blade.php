@extends('layouts.app')
@section('content')
    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <div class="px-2 py-3 my-5 text-center" id="response">
                    @if($task['result'] === true )
                        <div class="card">
                            <div class="card-body"><h5 class="card-title">{{$task['answer']['prompt_full']}}</h5>
                                @if ($task['answer']['status'] === 1)
                                    <p class="card-text">{{$task['answer']['answer']}}</p>
                                @else
                                    <div class="spinner-border m-5" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                @endif
                                @endif
                            </div>
                        </div>
                </div>
            </div>
            @endsection

@push('bottom-scripts')

<script>

    let id = {{$id}};
    let responseHtml = document.querySelector('#response');
    let counter = 1;
    let interval = 10000; // 10 секунд
    let intervalEnd = 120000 // 2 минуты.

    let fetchTast = () => {

        // Stop the recursion
        if ((interval * counter) >= intervalEnd) {
            responseHtml.innerHTML = '<div class="alert alert-secondary">In process...</div>';
        }

        fetch(`/api/task/get_task?task_id=${id}`)
            .then(response => response.json())
            .then(json => {

                if (json.result !== true) {
                    responseHtml.innerHTML = '<div class="alert alert-danger"> Something went wrong. <br> please refresh page </div>';
                }

                if (json.answer.status !== 1) {
                    setTimeout(() => fetchTast(), interval);
                    counter++;
                    return false
                }

                responseHtml.innerHTML = `<div class="card"> <div class="card-body"> <h5 class="card-title">${json.answer.prompt_full}</h5> <p class="card-text">${json.answer.answer} </p>`;

            }).catch(error => console.log(error));
    };


   let task = {!! json_encode($task) !!};

   if (task.result === false || task?.answer?.status !== 1) {
        fetchTast();
    }

</script>

@endpush
