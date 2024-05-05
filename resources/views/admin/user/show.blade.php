@extends('layouts.admin')
@section('content')
    <div class="card mb-5">
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><b>{{__('admin.User name')}}:</b> {{$user->name}}</li>
            <li class="list-group-item"><b>{{__('admin.Email')}}:</b> {{$user->email}}</li>
            <li class="list-group-item">
                <b>{{__('admin.Status')}}:</b>
                @if($user->status)
                    <svg style="fill: #198754" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                         fill="currentColor" class="bi bi-lightbulb-fill" viewBox="0 0 16 16">
                        <path
                            d="M2 6a6 6 0 1 1 10.174 4.31c-.203.196-.359.4-.453.619l-.762 1.769A.5.5 0 0 1 10.5 13h-5a.5.5 0 0 1-.46-.302l-.761-1.77a2 2 0 0 0-.453-.618A5.98 5.98 0 0 1 2 6m3 8.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1l-.224.447a1 1 0 0 1-.894.553H6.618a1 1 0 0 1-.894-.553L5.5 15a.5.5 0 0 1-.5-.5"/>
                    </svg>
                @else
                    <svg style="fill: gray;" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                         fill="currentColor" class="bi bi-lightbulb-off-fill" viewBox="0 0 16 16">
                        <path
                            d="M2 6c0-.572.08-1.125.23-1.65l8.558 8.559A.5.5 0 0 1 10.5 13h-5a.5.5 0 0 1-.46-.302l-.761-1.77a2 2 0 0 0-.453-.618A5.98 5.98 0 0 1 2 6m10.303 4.181L3.818 1.697a6 6 0 0 1 8.484 8.484zM5 14.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1l-.224.447a1 1 0 0 1-.894.553H6.618a1 1 0 0 1-.894-.553L5.5 15a.5.5 0 0 1-.5-.5M2.354 1.646a.5.5 0 1 0-.708.708l12 12a.5.5 0 0 0 .708-.708z"/>
                    </svg>
                @endif
            </li>
            <li class="list-group-item">
                <b>{{__('admin.Sys user')}}:</b>
                @if($user->sys_user) {{__('admin.Yes')}} @else {{__('admin.No')}} @endif
            </li>
            <li class="list-group-item"><b>{{__('admin.Created at')}}:</b> {{$user->created_at}}</li>
        </ul>
    </div>
    <a href="{{route('admin.user.index')}}" class="btn btn-danger col-md-1">
        {{ __('admin.Back') }}
    </a>
@endsection
