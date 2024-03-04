@extends('layouts.admin')
@section('content')
    <h2>New form</h2>
<!--    <a class="btn-link" href="">New form</a>-->

    @if ($allTypesTasks)
        <div class="row">
        @foreach($allTypesTasks['types_of_tasks'] as $k => $value)
            <div class="col-md-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{{ $k }}" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        {{ $value['name'] }}
                    </label>
                </div>
            </div>
        @endforeach
        </div>
    @endif

@endsection
