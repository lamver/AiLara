<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
        <meta name="generator" content="Hugo 0.84.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>AiLara dashboard</title>
        <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/dashboard/">
        @stack('top-scripts')
        @yield('stylesheet')
        @stack('styles')

        <!-- Bootstrap core CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu:regular,bold"/>
        <!-- Favicons -->
        <link rel="apple-touch-icon" href="/docs/5.0/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
        <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
        <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
        <link rel="manifest" href="/docs/5.0/assets/img/favicons/manifest.json">
        <link rel="mask-icon" href="/docs/5.0/assets/img/favicons/safari-pinned-tab.svg" color="#7952b3">
        <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon.ico">
        <meta name="theme-color" content="#7952b3">

        @stack('stylesheet')
        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
            }

            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                    font-size: 3.5rem;
                }
            }

            body {
                font-size: .875rem;
            }

            .feather {
                width: 16px;
                height: 16px;
                vertical-align: text-bottom;
            }

            /*
             * Sidebar
             */

            .sidebar {
                position: fixed;
                top: 0;
                /* rtl:raw:
                right: 0;
                */
                bottom: 0;
                /* rtl:remove */
                left: 0;
                z-index: 100; /* Behind the navbar */
                padding: 48px 0 40px 0; /* Height of navbar */
                box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
                overflow-y: auto;
            }

            @media (max-width: 767.98px) {
                .sidebar {
                    top: 5rem;
                }
            }

            .sidebar-sticky {
                position: relative;
                top: 0;
                height: calc(100vh - 48px);
                padding-top: .5rem;
                overflow-x: hidden;
                overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
            }

            .sidebar .nav-link {
                font-weight: 500;
                color: #333;
            }

            .sidebar .nav-link .feather {
                margin-right: 4px;
                color: #727272;
            }

            .sidebar .nav-link.active {
                color: #2470dc;
            }

            .sidebar .nav-link:hover .feather,
            .sidebar .nav-link.active .feather {
                color: inherit;
            }

            .sidebar-heading {
                font-size: .75rem;
                text-transform: uppercase;
            }

            /*
             * Navbar
             */

            .navbar-brand {
                padding-top: .75rem;
                padding-bottom: .75rem;
                font-size: 1rem;
                background-color: rgba(0, 0, 0, .25);
                box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25);
            }

            .navbar .navbar-toggler {
                top: .25rem;
                right: 1rem;
            }

            .navbar .form-control {
                padding: .75rem 1rem;
                border-width: 0;
                border-radius: 0;
            }

            .form-control-dark {
                color: #fff;
                background-color: rgba(255, 255, 255, .1);
                border-color: rgba(255, 255, 255, .1);
            }

            .form-control-dark:focus {
                border-color: transparent;
                box-shadow: 0 0 0 3px rgba(255, 255, 255, .25);
            }


        </style>


        <!-- Custom styles for this template -->
        <!-- <link href="dashboard.css" rel="stylesheet"> -->
    </head>
    <body>

        <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">AiLara</a>
            <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
            @auth()
            <div class="navbar-nav flex-row">
                <div class="nav-item text-nowrap">
                    <a href="{{ route('admin.user.show', \Illuminate\Support\Facades\Auth::id()) }}" class="nav-link px-3">{{ \Illuminate\Support\Facades\Auth::user()->getAuthIdentifierName() }}</a>
                </div>
                <div class="nav-item text-nowrap">
                    <form method="post" action="{{route('logout')}}">
                        @csrf
                        <button type="submit" class="nav-link px-3">{{ __('admin.sign_out') }}</button>
                    </form>
                </div>
            </div>
            @endauth
        </header>
        <div class="container-fluid">
            <div class="row">
                <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                    <div class="position-sticky pt-3">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="{{route('admin.index')}}" {{\Illuminate\Support\Facades\Route::is('admin.index') ? 'disable' : ''}}>
                                    <span data-feather="home"></span>
                                    {{ __('admin.dashboard') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('accessUi.') }}">
                                    <span data-feather="file"></span>
                                    {{ __('admin.RBAC') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.configuration') }} ">
                                    <span data-feather="shopping-cart"></span>
                                    {{ __('admin.configuration') }}
                                </a>
                                <ul>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('admin.configuration.robots_txt') }}">
                                            <span data-feather="bar-chart-2"></span>
                                            Robots TXT
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <span data-feather="users"></span>
                                    {{ __('admin.ai_search') }}
                                </a>
                                <ul>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('admin.ais.commonData') }}">
                                            <span data-feather="bar-chart-2"></span>
                                            {{ __('admin.common_data') }}
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('admin.ais.aiForms') }}">
                                            <span data-feather="bar-chart-2"></span>
                                            {{ __('admin.ai_forms') }}
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <span data-feather="users"></span>
                                    {{ __('admin.modules') }}
                                </a>
                                <ul>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('admin.ais.commonData') }}">
                                            <span data-feather="bar-chart-2"></span>
                                            {{ __('admin.configuration') }}
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">
                                            <span data-feather="users"></span>
                                            {{ __('admin.blog') }}
                                        </a>
                                        <ul>
                                            <li>
                                                <a class="nav-link" href="{{ route('admin.blog.post.index') }}">
                                                    <span data-feather="bar-chart-2"></span>
                                                    {{ __('admin.posts') }}
                                                </a>
                                            </li>
                                            <li>
                                                <a class="nav-link" href="{{ route('admin.blog.category.index') }}">
                                                    <span data-feather="bar-chart-2"></span>
                                                    {{ __('admin.category') }}
                                                </a>
                                            </li>
                                            <li>
                                                <a class="nav-link" href="{{ route('admin.blog.import.index') }}">
                                                    <span data-feather="bar-chart-2"></span>
                                                    {{ __('admin.import') }}
                                                </a>
                                            </li>
                                        </ul>

                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('telegram-bots.index') }}">
                                    <span data-feather="bar-chart-2"></span>
                                    {{ __('admin.telegram_bots') }}
                                </a>
                                <a class="nav-link" href="{{ route('admin.ais.pages') }}">
                                    <span data-feather="bar-chart-2"></span>
                                    {{ __('admin.Routes') }}
                                </a>
                                <a class="nav-link" href="{{ route('admin.update') }}">
                                    <span data-feather="bar-chart-2"></span>
                                    {{ __('admin.update_app') }}
                                </a>
                                <a class="nav-link" href="{{ route('admin.logs') }}">
                                    <span data-feather="bar-chart-2"></span>
                                    {{ __('admin.Logs') }}
                                </a>
                            </li>
                            <li class="nav-item">

                                <a class="nav-link" href="/admin/translations">
                                    <span data-feather="bar-chart-2"></span>
                                    {{ __('admin.Managing transfers') }}
                                </a>
                            </li>
                             <li class="nav-item">
                               <a class="nav-link" href="{{ route('admin.user.index') }}">
                                    <span data-feather="bar-chart-2"></span>
                                    {{__('Users')}}
                                </a>
                             </li>

                            <li class="nav-item">
                               <a class="nav-link" href="{{ route('admin.backup.index') }}">
                                    <span data-feather="bar-chart-2"></span>
                                    {{__('backup')}}
                                </a>
                             </li>

                            @if(isset($languages))
                            <li class="nav-item">
                                <div class="d-flex flex-row bd-highlight">
                                    <select class="form-select" id="setLang" style="width: 100px; margin-left: auto; margin-top: 23px; margin-right: 25px;">
                                        @php foreach ($languages as $lang): @endphp
                                        <option @if(trans()->getLocale() === $lang) selected @endif value="{{$lang}}">{{$lang}}</option>
                                        @php endforeach; @endphp
                                    </select>
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>
                </nav>

                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">@yield('page_title')</h1>
                        @yield('page_options')
                    </div>
                    @if (session()->has('message_warning'))
                        <div class="alert alert-warning">
                            {{ session('message_warning') }}
                        </div>
                    @endif
                    <div id="main_content"></div>
                    @yield('content')
                    <canvas class="my-4 w-100" id="myChart" width="900" height="380"></canvas>

<!--                    <h2>Section title</h2>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Header</th>
                                    <th scope="col">Header</th>
                                    <th scope="col">Header</th>
                                    <th scope="col">Header</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1,001</td>
                                    <td>random</td>
                                    <td>data</td>
                                    <td>placeholder</td>
                                    <td>text</td>
                                </tr>
                                <tr>
                                    <td>1,002</td>
                                    <td>placeholder</td>
                                    <td>irrelevant</td>
                                    <td>visual</td>
                                    <td>layout</td>
                                </tr>
                                <tr>
                                    <td>1,003</td>
                                    <td>data</td>
                                    <td>rich</td>
                                    <td>dashboard</td>
                                    <td>tabular</td>
                                </tr>
                                <tr>
                                    <td>1,003</td>
                                    <td>information</td>
                                    <td>placeholder</td>
                                    <td>illustrative</td>
                                    <td>data</td>
                                </tr>
                                <tr>
                                    <td>1,004</td>
                                    <td>text</td>
                                    <td>random</td>
                                    <td>layout</td>
                                    <td>dashboard</td>
                                </tr>
                                <tr>
                                    <td>1,005</td>
                                    <td>dashboard</td>
                                    <td>irrelevant</td>
                                    <td>text</td>
                                    <td>placeholder</td>
                                </tr>
                                <tr>
                                    <td>1,006</td>
                                    <td>dashboard</td>
                                    <td>illustrative</td>
                                    <td>rich</td>
                                    <td>data</td>
                                </tr>
                                <tr>
                                    <td>1,007</td>
                                    <td>placeholder</td>
                                    <td>tabular</td>
                                    <td>information</td>
                                    <td>irrelevant</td>
                                </tr>
                                <tr>
                                    <td>1,008</td>
                                    <td>random</td>
                                    <td>data</td>
                                    <td>placeholder</td>
                                    <td>text</td>
                                </tr>
                                <tr>
                                    <td>1,009</td>
                                    <td>placeholder</td>
                                    <td>irrelevant</td>
                                    <td>visual</td>
                                    <td>layout</td>
                                </tr>
                                <tr>
                                    <td>1,010</td>
                                    <td>data</td>
                                    <td>rich</td>
                                    <td>dashboard</td>
                                    <td>tabular</td>
                                </tr>
                                <tr>
                                    <td>1,011</td>
                                    <td>information</td>
                                    <td>placeholder</td>
                                    <td>illustrative</td>
                                    <td>data</td>
                                </tr>
                                <tr>
                                    <td>1,012</td>
                                    <td>text</td>
                                    <td>placeholder</td>
                                    <td>layout</td>
                                    <td>dashboard</td>
                                </tr>
                                <tr>
                                    <td>1,013</td>
                                    <td>dashboard</td>
                                    <td>irrelevant</td>
                                    <td>text</td>
                                    <td>visual</td>
                                </tr>
                                <tr>
                                    <td>1,014</td>
                                    <td>dashboard</td>
                                    <td>illustrative</td>
                                    <td>rich</td>
                                    <td>data</td>
                                </tr>
                                <tr>
                                    <td>1,015</td>
                                    <td>random</td>
                                    <td>tabular</td>
                                    <td>information</td>
                                    <td>text</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>-->
                </main>
            </div>
        </div>

        @stack('bottom-scripts')
        <script>

            let requestUri = location.pathname;
            let link = document.querySelector(`#sidebarMenu .nav-item a[href*="${requestUri}"]`);
            if(!!link) {
                link.classList.add('active');
            }

            let setLang = document.getElementById('setLang');
            setLang.addEventListener('change', function (){
                let selectedLang = this.value;
                fetch(`/admin/setLang/${selectedLang}`)
                    .then(() => {
                        updateLanguageInUrl(selectedLang);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });

            });

            function updateLanguageInUrl(newLang) {
                let href = location.href;
                let languages = {!! json_encode($languages ?? []) !!};
                let langRegex = new RegExp('/(' + languages.join('|') + ')/');

                if (langRegex.test(href)) {
                    href = href.replace(langRegex, '/' + newLang + '/');
                } else if (newLang !== 'en') {
                    href = `${location.origin}/${newLang}${location.pathname}`;
                }

                // Remove any occurrence of 'en/' from the URL
                href = href.replace(/en\//, '');

                location.href = href;
            }
        </script>

    </body>
</html>

