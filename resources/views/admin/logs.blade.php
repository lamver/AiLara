@extends('layouts.admin')
@section('content')
@foreach($files as $logFile)
    @if ($logFile == '.gitignore') @continue @endif
    <a href="?logFile={{ $logFile }}">{{ $logFile }}</a><br>
@endforeach
    @if (isset($viewFile))
        {!!   str_replace(["\n", "\r"], "<br>", $viewFile) !!}
    @endif
@endsection
