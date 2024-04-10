@extends('layouts.admin')
@section('content')
    @php $previousStepError = false; @endphp
    @if(\App\Services\Update\Update::downloadArchiveRepository())
        <div>The update archive was successfully downloaded</div>
    @else
        @php $previousStepError = 'The update archive was error downloaded'; @endphp
    @endif

    @if(!$previousStepError && \App\Services\Update\Update::extractArchiveRepository())
        <div>The update archive was successfully unpacked</div>
    @else
        {{ $previousStepError }}
        @php $previousStepError = 'The update archive was error unpacked'; @endphp
    @endif

    <div style="max-height: 300px; overflow-y: scroll">
    @if(
    !$previousStepError
    && is_array($filesCandidateUpdate = \App\Services\Update\Update::getFileToCandidateUpdate())
    )
        @foreach($filesCandidateUpdate as $index => $fileCandidate)
            <div>{{ $index }}<br>
                {{ $fileCandidate['path'] }}<br>
                {{ $fileCandidate['pathWithoutDirExtract'] }}<br>
                @if(is_bool($result = \App\Services\Update\Update::updateFile($fileCandidate)))
                    ok
                @else
                    {{ $result }}
                @endif
            </div>
        @endforeach
    @else
        {{ $previousStepError }}
        @php $previousStepError = 'Files candidate to update error'; @endphp
    @endif
    </div>
    <div style="max-height: 300px; overflow-y: scroll"> {{ getcwd() }}
    @if(!$previousStepError)
        @php exec('cd ../'); exec('composer install', $output, $return); @endphp
        @if($return !== 0)
            @foreach($output as $result)
                {{ $result }}<br>
            @endforeach
            Composer install executed successfully
        @else
            @php $previousStepError = 'Error executing composer install'; @endphp
        @endif
    @else
        {{ $previousStepError }}
    @endif
    </div>
@endsection
