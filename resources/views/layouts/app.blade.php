<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    {!! JsonLd::generate() !!}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="/images/codeflow_favicon.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    @yield('stylesheet')
    @stack('meta_noindex')
    @stack('styles')
    @stack('top-scripts')
    @yield('breadcrumbs-json-ld')
</head>
<body>
@yield('header-navbar')
{{--        <div class="container">
            <header class="d-flex flex-wrap justify-content-center py-3 mb-4">
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li>
                        <a href="<?php echo Route::currentRouteName() == 'index' ? '#home' : route('index')?>" class="nav-link px-2 fw-bolder">
                            <img title="{{ \Illuminate\Support\Facades\Config::get('ailara.logoTitle') }}" alt="Нейросеть для разбора сноведений" width="{{ \Illuminate\Support\Facades\Config::get('ailara.logoWidthPx') }}" height="{{ \Illuminate\Support\Facades\Config::get('ailara.logoHeightPx') }}" src="{{ \Illuminate\Support\Facades\Config::get('ailara.logoPath') }}"/>
                        </a>
                    </li>
                </ul>
                <ul class="nav nav-pills">
                    <div class="d-flex" id="auth-btn"></div>
                    @if(\Illuminate\Support\Facades\Auth::check() && Route::currentRouteName() == 'dashboard')
                        <a class="navbar-btn btn btn-light btn-sm" style="height: 36px" title="Выйти из аккаунта" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" href="{{ route('logout') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                                <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                            </svg>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    @endif
                    <li class="nav-item">
                        <a href="#" id="toggle-main-theme-button" class="nav-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-brightness-high-fill" viewBox="0 0 16 16">
                                <path d="M12 8a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
                            </svg>
                        </a>
                    </li>
                </ul>
            </header>
        </div>--}}
<div class="container">
    @yield('breadcrumbs')
</div>
@yield('content')
<div class="container">
    <footer class="py-3 my-4">
        <ul class="nav justify-content-center pb-3 mb-3">
        </ul>
        <p class="text-center text-muted">© @php echo date("Y", time()) @endphp Powered by <a target="_blank" href="https://aisearch.ru">AiSearch TECH</a>, Inc</p>
    </footer>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script>
    const html = document.querySelector('html');

    const iconLightMode = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-brightness-high-fill" viewBox="0 0 16 16">' +
        '<path d="M12 8a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>' +
        '</svg>';

    const iconDarkMode = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-moon-stars-fill" viewBox="0 0 16 16">' +
        '<path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/>' +
        '<path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"/>' +
        '</svg>';

    function toggleTheme() {
        let currentTheme = html.getAttribute('data-bs-theme');

        if (currentTheme === 'dark') {
            setMainTheme('light');
        } else {
            setMainTheme('dark');
        }
    }

    const toggleButton = document.getElementById('toggle-main-theme-button');
    toggleButton.addEventListener('click', toggleTheme);

    function setMainTheme(theme = 'light') {

        if (theme === 'auto') {
            let userTheme;

            if ((userTheme = localStorage.getItem("main-theme-set-user"))) {
                if (userTheme == 'dark') {
                    setDarkTheme();
                    return;
                }

                setLightTheme();

                return;
            }

            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                setDarkTheme();

                return;
            }

            setLightTheme()

            return;
        }

        if (theme === 'light') {
            setLightTheme()

            return;
        }

        if (theme === 'dark') {
            setDarkTheme()

            return;
        }
    }

    function setLightTheme()
    {
        html.setAttribute('data-bs-theme', 'light');
        toggleButton.innerHTML = iconDarkMode;
        localStorage.setItem("main-theme-set-user", 'light');
    }

    function setDarkTheme()
    {
        html.setAttribute('data-bs-theme', 'dark');
        toggleButton.innerHTML = iconLightMode;
        localStorage.setItem("main-theme-set-user", 'dark');
    }

    setMainTheme('auto');

    window.onload = function() {
        var container = document.getElementById('auth-btn');
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    container.innerHTML = xhr.responseText;
                } else {
                    container.innerHTML = 'Unable to load content.';

                    setTimeout(function() {
                        xhr.open('GET', '/auth/btn.html', true);
                        xhr.send();
                    }, 5000);
                }
            }
        };
        xhr.open('GET', '/auth/btn.html', true);
        xhr.send();
    };

</script>
@stack('bottom-scripts')
</body>
{!! (new \App\Settings\SettingGeneral())->counter_external_code !!}
</html>
