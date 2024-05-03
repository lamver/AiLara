@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                @php $taskUserParams = json_decode($task['user_params'], true) @endphp
                @foreach($taskUserParams as $paramKey => $paramValue)
                    @if($paramKey == 'prompt') @continue @endif
                    {{ \App\Models\Modules\AiForm\AiForm::fillParamName($task['form_id'], $task['task_id'], $paramKey) }}: {{ $paramValue }} <br>
                @endforeach
            </div>
            <div class="col-md-10">
                <h1>{{ $taskUserParams['prompt'] }}</h1>
                <div class="px-2 py-3" id="response">
                    @if($task['status'] === \App\Models\Tasks::STATUS_DONE_SUCCESSFULLY)
                        <div class="card">
                            <div class="card-body">
                                <h1 class="card-title">
                                    {{--{{ $task['answer']['prompt_full'] }}--}}
                                </h1>
                                <h2></h2>
                                    <p class="card-text">
                                        {!! $task['result'] !!}
                                    </p>
                            </div>
                        </div>
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
    @if($task['status'] === \App\Models\Tasks::STATUS_CREATED)
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

        fetch(`{{ route('ajax.ai-form.getTask', ['id' => $id]) }}`)
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

                console.log(json);

                responseHtml.innerHTML = `<div class="card"> <div class="card-body"><p class="card-text">${json.answer.answer} </p>`;

            }).catch(error => console.log(error));
    };


   let task = {!! json_encode($task) !!};

   if (task.result === false || task?.answer?.status !== 1) {
        fetchTast();
    }

   @endif

</script>

@endpush
