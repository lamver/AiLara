@extends('layouts.app')
@section('content')
    <div class="container">
        <div
            class="container error_container d-flex justify-content-center align-items-center flex-column w-100 h-100 p-5 text-center">
            <h2 class="error_message text-center mb-3">
                {{ __("Boo! You don't have an internet connection") }}

            </h2>
            <p class="error_paragraph text-center mb-5">
                {{ __('Please check your network connection and try again.') }}
            </p>
            <a href="/" class="btn btn-primary error_btn">
                {{ __("Back to Home Page") }}
                </a>
        </div>
    </div>
@endsection
