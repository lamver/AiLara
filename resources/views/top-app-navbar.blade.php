@php
    $settings = \App\Helpers\Settings::load();
@endphp
<nav class="navbar navbar-expand-lg shadow-sm">
    <div class="container container-fluid">
        @if((new Mobile_Detect())->isMobile())
            <span style="width: 26px;" id="previousUrl"></span>
        @endif
        <a class="navbar-brand" href="<?php echo Route::currentRouteName() == 'index' ? '#home' : route('index')?>">
            <img title="{{ $settings->site_name  }}" alt="Нейросеть для разбора сноведений" width="{{ $settings->logo_width_px }}" height="{{ $settings->logo_height_px }}" src="{{ $settings->logo_path }}"/>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainAppNavbar" aria-controls="mainAppNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
            </svg>
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

@push('bottom-scripts')
    @if((new Mobile_Detect())->isMobile())
        <script>
            function addBackLink() {
                var previousUrl = document.referrer;
                var currentUrl = window.location.href;

                if (previousUrl.includes(window.location.hostname) && previousUrl !== currentUrl) {
                    var previousLink = document.createElement("a");
                    previousLink.href = previousUrl;
                    previousLink.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
      </svg>`;

                    if (!isHomePage()) {
                        var previousLinkContainer = document.getElementById("previousUrl");
                        previousLinkContainer.appendChild(previousLink);
                    } else {

                    }
                }
            }

            function isHomePage() {
                let appUrl = "{{ env('APP_URL') }}";

                if (appUrl.charAt(appUrl.length - 1) !== "/") {
                    appUrl += "/";
                }

                if (window.location.href === appUrl) {
                    return true;
                }

                return false;
            }

            addBackLink();
        </script>
    @endif

@endpush
