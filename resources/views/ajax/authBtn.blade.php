@guest
    <li class="nav-item">
        <a class="navbar-btn btn" href="{{ route('login') }}">{{ __('Login') }}</a>
        <a class="navbar-btn btn" href="{{ route('register') }}">{{ __('Create account') }}</a>
    </li>
@else
    <li class="nav-item">
        <a href="{{ route('dashboard') }}" class="navbar-btn btn">
            @if (empty($user->avatar))
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                </svg>
            @else
                <img src="{{ $user->avatar }}" alt="mdo" width="26" height="26" class="rounded-circle">
            @endif
            {{ __('Dashboard') }}
        </a>
    </li>
    <li class="nav-item text-nowrap">
        <form method="post" action="{{route('logout')}}">
            @csrf
            <button type="submit" class="nav-link px-3">{{ __('admin.Sign out') }}</button>
        </form>
    </li>
@endguest
