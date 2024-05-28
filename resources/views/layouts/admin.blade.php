<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
        <meta name="generator" content="Hugo 0.84.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ app(\App\Settings\SettingGeneral::class)->app_name }}</title>
        <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/dashboard/">
        @stack('top-scripts')
        @yield('stylesheet')
        @stack('styles')
        <!-- Bootstrap core CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu:regular,bold"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css">
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
                        <button type="submit" class="nav-link px-3">{{ __('admin.Sign out') }}</button>
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
                                    {{ __('admin.Dashboard') }}
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
                                    {{ __('admin.Configuration') }}
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
                                    {{ __('admin.Modules') }}
                                </a>
                                <ul>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('admin.modules.main.config') }}">
                                            <span data-feather="bar-chart-2"></span>
                                            {{ __('admin.Configuration') }}
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('admin.module.ai-form.settings') }}">
                                            <span data-feather="bar-chart-2"></span>
                                            {{ __('admin.Ai forms') }}
                                        </a>
                                        <ul>
                                            <li>
                                                <a class="nav-link" href="{{ route('admin.module.ai-form.index') }}">
                                                    <span data-feather="bar-chart-2"></span>
                                                    {{ __('admin.Forms') }}
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">
                                            <span data-feather="users"></span>
                                            {{ __('admin.Blog') }}
                                        </a>
                                        <ul>
                                            <li>
                                                <a class="nav-link" href="{{ route('admin.blog.post.index') }}">
                                                    <span data-feather="bar-chart-2"></span>
                                                    {{ __('admin.Posts') }}
                                                </a>
                                            </li>
                                            <li>
                                                <a class="nav-link" href="{{ route('admin.blog.category.index') }}">
                                                    <span data-feather="bar-chart-2"></span>
                                                    {{ __('admin.Category') }}
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ route('admin.comment.index') }}">
                                                    <span data-feather="bar-chart-2"></span>
                                                    {{ __('admin.Comments') }}
                                                </a>
                                            </li>
                                            <li>
                                                <a class="nav-link" href="{{ route('admin.blog.import.index') }}">
                                                    <span data-feather="bar-chart-2"></span>
                                                    {{ __('admin.Import') }}
                                                </a>
                                            </li>
                                            <li>
                                                <a class="nav-link" href="{{ route('admin.blog.settings.index') }}">
                                                    <span data-feather="bar-chart-2"></span>
                                                    {{ __('admin.Settings') }}
                                                </a>
                                            </li>
                                        </ul>

                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('telegram-bots.index') }}">
                                    <span data-feather="bar-chart-2"></span>
                                    {{ __('admin.Telegram bots') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.ais.pages') }}">
                                    <span data-feather="bar-chart-2"></span>
                                    {{ __('admin.Pages') }}
                                </a>
                            </li>
                            <li class="nav-item">
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
                                <a class="nav-link" href="{{ route('admin.update') }}">
                                    <span data-feather="bar-chart-2"></span>
                                    {{ __('admin.Update app') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.optimize.app') }}">
                                    <span data-feather="bar-chart-2"></span>
                                    {{ __('admin.Optimize app') }}
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

                </main>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="aiModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">{{__('admin.Field')}}: <span></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">{{__('admin.Ai_type')}}</label>
                            <select class="form-select" id="typeTask">
                                <option value="1">{{__('admin.task_text')}}</option>
                                <option value="2">{{__('admin.task_image')}}</option>
                                <option value="3">{{__('admin.task_write_text')}}</option>
                                <option value="6">{{__('admin.task_answer')}}</option>
                                <option value="7">{{__('admin.task_write_rewrite')}}</option>
                                <option value="11">{{__('admin.task_make_title')}}</option>
                                <option value="20">{{__('admin.task_seo_title')}}</option>
                                <option value="21">{{__('admin.task_seo_description')}}</option>
                                <option value="22">{{__('admin.task_seo_article')}}</option>
                            </select>
                        </div>

                        <div class="mb-3" id="basicCheck" style="display: none">
                            <div class="form-check form-switch">
                                <input class="form-check-input" checked type="checkbox" name="basic" id="basic">
                                <label class="form-check-label" for="basic">{{__('admin.Basic')}}</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{__('admin.Text')}}</label>
                            <textarea class="form form-control" name="aiForm" placeholder="{{ __('admin.Ask Ai') }}"></textarea>
                        </div>

                        <div class="alert alert-danger" style="display: none">
                        </div>

                        <div class="mb-3" id="innerBox" style="display: none">
                            <div class="mb-2">{{__('admin.Result')}}</div>
                            <div id="innerResult" class="shadow-lg p-3 mb-5 bg-body rounded" style="text-align: center"></div>
                        </div>

                        <button id="createAi" type="button" class="btn btn-primary">
                            <span>{{__('admin.Create')}}</span>
                            <svg style="fill: rgb(255, 255, 255); display: none" width="24" height="24" viewBox="0 0 24 24"
                                 xmlns="http://www.w3.org/2000/svg">
                                <circle cx="4" cy="12" r="3">
                                    <animate id="spinner_qFRN" begin="0;spinner_OcgL.end+0.25s" attributeName="cy"
                                             calcMode="spline" dur="0.6s" values="12;6;12"
                                             keySplines=".33,.66,.66,1;.33,0,.66,.33"></animate>
                                </circle>
                                <circle cx="12" cy="12" r="3">
                                    <animate begin="spinner_qFRN.begin+0.1s" attributeName="cy" calcMode="spline" dur="0.6s"
                                             values="12;6;12" keySplines=".33,.66,.66,1;.33,0,.66,.33"></animate>
                                </circle>
                                <circle cx="20" cy="12" r="3">
                                    <animate id="spinner_OcgL" begin="spinner_qFRN.begin+0.2s" attributeName="cy"
                                             calcMode="spline" dur="0.6s" values="12;6;12"
                                             keySplines=".33,.66,.66,1;.33,0,.66,.33"></animate>
                                </circle>
                            </svg>
                        </button>

                    </div>
                    <div class="modal-footer">
                        <button type="button" id="insertBtn" class="btn btn-primary" disabled>
                            {{__('admin.Insert')}}
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('admin.Close')}}</button>
                    </div>
                </div>
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

            const AiManager = {

                aiCreateTaskRoute: '{{route('admin.createAiTask')}}',
                aiGetTaskRoute: '{{route('admin.getAiTask')}}',
                modalEl: document.getElementById('aiModal'),
                createAiBtn: document.getElementById('createAi'),
                insertBtn: document.getElementById('insertBtn'),
                innerBox: document.getElementById('innerBox'),
                selectTypeTask: document.getElementById('typeTask'),
                aiResult: {},
                basicCheckValue: false,
                attemptCount: 0,
                tries: 25,
                inProcess: false,

                init: function () {

                    this.modalEl.addEventListener('show.bs.modal', (event) => {
                        this.modalEl.dataset.typeId = event.relatedTarget.dataset.typeId
                        let modelTitle = this.modalEl.querySelector('.modal-header .modal-title span');
                        modelTitle.innerText = document.querySelector(`[for='${event.relatedTarget.dataset.typeId}']`)?.innerText ?? ""

                    });

                    this.modalEl.addEventListener('hide.bs.modal', this.modalClose.bind(this));
                    this.createAiBtn.addEventListener('click', this.createAiFunc.bind(this));
                    this.insertBtn.addEventListener('click', this.insertFunc.bind(this));
                    this.selectTypeTask.addEventListener('change', this.selectTypeTaskEvent.bind(this));

                    window.addEventListener('beforeunload', this.beforeunload.bind(this));

                },
                modalClose: function (event) {
                    if(this.inProcess && !confirm('{{__('admin.Close')}} ?')) {
                        event.preventDefault();
                        event.stopImmediatePropagation();
                        return false
                    }

                    this.reset();
                },
                beforeunload: function (event) {
                    if (this.inProcess) {
                        // Показываем диалоговое окно подтверждения
                        let confirmationMessage = '{{__('admin.Are you sure')}} ?';
                        event.returnValue = confirmationMessage; // Необходимо для старых версий браузеров
                        // Если пользователь согласился, возвращаем null (иначе диалоговое окно отменится)
                        return confirmationMessage;
                    }
                },
                selectTypeTaskEvent: function (even) {
                    let basicCheck = this.modalEl.querySelector('#basicCheck');
                    basicCheck.style.display = "none";
                    this.basicCheckValue = false;

                    if((even.target.value*1) === 2) {
                        basicCheck.style.display = "block";
                        this.basicCheckValue = true;
                    }

                },
                createAiFunc: async function () {

                    let text = this.modalEl.querySelector(".modal-body [name='aiForm']").value
                    let type = this.modalEl.querySelector(".modal-body #typeTask").value;
                    this.inProcess = true;
                    this.errorHandler(false);

                    if (text.length < 3) return;

                    this.createAiBtnAction(true);
                    let data = {prompt: text, type_task: type};

                    if(this.basicCheckValue) {
                        data.basic = 1;
                        data.size =  "1024x1024";
                    }

                    let result = await this.fetchAi(this.aiCreateTaskRoute, data)

                    if (result.result) {
                        const intervalId = setInterval(async () => {
                            this.aiResult = await this.fetchGetAiTask(result.task_id, intervalId);
                            if (this.aiResult?.status !== 1) return;

                            this.createAiBtnAction(false);
                            this.innerBox.style.display = 'block';
                            this.insertBtn.disabled = false;
                            this.attemptCount = 0;

                            if (this.aiResult?.url_files?.length > 0) {
                                this.responseImg();
                                return false;
                            }
                            this.aiResult.answer = this.aiResult.answer.replace(/<\/?[^>]+(>|$)/g, "");
                            this.innerBox.querySelector('#innerResult').innerText = this.aiResult.answer;
                            this.inProcess = false;


                        }, 3000);

                        return;
                    }

                    this.errorHandler(true, result.message);
                    this.reset();

                },
                fetchAi: async function (url, data) {

                    let result = await fetch(url, {
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-Token": document.querySelector('input[name="_token"]').value
                        },
                        method: "post",
                        credentials: "same-origin",
                        body: JSON.stringify(data)
                    });

                    return await result.json();

                },
                fetchGetAiTask: async function (id, intervalId) {

                    let response = await this.fetchAi(this.aiGetTaskRoute, {id: id});
                    let {result, answer} = await response;

                    if (result && answer.status === 1 || ++this.attemptCount >= this.tries) {
                        clearInterval(intervalId);
                        return answer;
                    }

                    return answer;

                },
                responseImg: function () {

                    let html = "";
                    let index = 0;
                    for (const img of this.aiResult.url_files) {
                        index++
                        html += `<input type="radio" name="img" id="myCheckbox${index}" style="display: none" value="${img}">
                         <label for="myCheckbox${index}"><img src="${img}" ></label>`;
                    }

                    this.innerBox.querySelector('#innerResult').innerHTML = html;

                    for (let el of document.querySelectorAll('#innerResult input[type="radio"]')) {
                        el.addEventListener('change', () => {
                            this.aiResult = {'answer': el.value}
                        });
                    }

                },
                insertFunc: function () {

                    document.getElementById(this.modalEl.dataset.typeId).value = this.aiResult.answer;
                    bootstrap.Modal.getInstance(this.modalEl).hide();

                },
                createAiBtnAction: function (status) {

                    if (status) {
                        this.createAiBtn.disabled = true;
                        this.createAiBtn.querySelector('span').style.display = "none";
                        this.createAiBtn.querySelector('svg').style.display = "block";
                        return;
                    }

                    this.createAiBtn.disabled = false;
                    this.createAiBtn.querySelector('span').style.display = "block";
                    this.createAiBtn.querySelector('svg').style.display = "none";

                },
                errorHandler: function (show, msg = "") {
                    let dangerBox = this.modalEl.querySelector('.alert-danger');

                    if(show) {
                        dangerBox.innerText = msg;
                        dangerBox.style.display = 'block';
                        return;
                    }

                    dangerBox.innerText = "";
                    dangerBox.style.display = 'none';

                },
                reset: function () {
                    this.innerBox.querySelector('#innerResult').innerText = "";
                    this.innerBox.style.display = 'none';
                    this.insertBtn.disabled = true;
                    this.modalEl.dataset.typeId = ""
                    this.modalEl.querySelector(".modal-body [name='aiForm']").value = "";
                    this.aiResult = {};
                    this.createAiBtnAction(false);
                    this.inProcess = false;
                }

            };

            AiManager.init();

        </script>

    </body>
</html>

