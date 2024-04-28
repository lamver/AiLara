<nav class="navbar navbar-expand-lg shadow-sm">
    <div class="container container-fluid">
        <a class="navbar-brand" href="<?php echo Route::currentRouteName() == 'index' ? '#home' : route('index')?>">
            <img title="{{ \Illuminate\Support\Facades\Config::get('ailara.logoTitle') }}" alt="Нейросеть для разбора сноведений" width="{{ \Illuminate\Support\Facades\Config::get('ailara.logoWidthPx') }}" height="{{ \Illuminate\Support\Facades\Config::get('ailara.logoHeightPx') }}" src="{{ \Illuminate\Support\Facades\Config::get('ailara.logoPath') }}"/>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainAppNavbar" aria-controls="mainAppNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainAppNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
<!--                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>-->

            </ul>
            <div class="d-flex">
                <ul class="nav nav-pills" id="auth-btn"  data-bs-theme="dark">

                </ul>
                <ul class="nav nav-pills" data-bs-theme="dark">
                <li class="nav-item">
                    <a href="#" id="toggle-main-theme-button" class="nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-brightness-high-fill" viewBox="0 0 16 16">
                            <path d="M12 8a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
                        </svg>
                    </a>
                </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
