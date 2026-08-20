<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>HRMS</title>

    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.ico') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.css">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=Nunito">
    <link rel="stylesheet" href="{{ asset('dashboard/css/main.css') }}">
    {{-- @if (auth()->user()->theme_mode === 'one-ui')
        <link href="{{ asset('dashboard/qui/css/one-ui.css') }}" rel="stylesheet">
    @elseif (auth()->user()->theme_mode === 'win-ui')
        <link href="{{ asset('dashboard/qui/css/win-ui.css') }}" rel="stylesheet">
    @elseif (auth()->user()->theme_mode === 'q-ui')
        <link href="{{ asset('dashboard/qui/css/q-ui.css') }}" rel="stylesheet">
    @elseif (auth()->user()->theme_mode === 'glass-ui')
        <link href="{{ asset('dashboard/qui/css/glass-ui.css') }}" rel="stylesheet">
    @else
        <link href="{{ asset('dashboard/qui/css/one-ui.css') }}" rel="stylesheet">
    @endif --}}
    <link rel="stylesheet" href="{{ asset('dashboard/qui/css/one-ui.css') }}">
</head>

<body class="accent-magenta">
    <aside class="qsidebar">
        {{-- HEADER --}}
        <div class="qsidebar-header">
            {{-- <a href="{{ route('profile') }}"
                class="qhover-btn transparent qrounded-sm qp-2 d-flex qm-2 align-items-center">
                <img class="rounded-circle" width="60" height="60"
                    src="{{ asset(auth()->user()->avatar ? 'uploads/' . auth()->user()->avatar : 'assets/img/default_avatar.png') }}"
                    alt="">

                <div class="qpl-2 qtext-fill-primary">
                    <h5 class="qsubtitle">{{ auth()->user()->emp_id }}</h5>
                    <p class="qcaption">{{ auth()->user()->name }}</p>
                </div>
            </a> --}}
        </div>

        <hr class="qmt-0 qmb-2 qmx-4 qdivider">

        {{-- MENU --}}
        <div class="qsidebar-body">
            <ul class="qmenu">

                {{-- HOME --}}
                <li class="qmenu-item">
                    <a href="{{ route('home') }}" class="qmenu-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="fa-solid fa-house qmenu-icon"></i>
                        <span class="qmenu-label">Home</span>
                    </a>
                </li>

                @foreach ($menus as $menu)
                    @if ($menu->permission)
                        @can($menu->permission)
                            <x-menu-item :menu="$menu" />
                        @endcan
                    @else
                        @if (empty($menu->permission))
                            <x-menu-item :menu="$menu" />
                        @endif
                    @endif
                @endforeach
            </ul>
        </div>

        {{-- <div class="qsidebar-footer qmb-3">
            <hr class="qmb-2 qmt-2 qmx-4 qdivider">
            <ul class="qmenu">
                <li class="qmenu-item">
                    <a href="{{ route('profile') }}"
                        class="qmenu-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                        <div class="qmenu-icon">
                            <img class="rounded-circle qborder {{ request()->routeIs('profile') ? 'qborder-accent' : '' }}"
                                width="24" height="24"
                                src="{{ asset(auth()->user()->avatar ? 'uploads/' . auth()->user()->avatar : 'assets/img/default_avatar.png') }}"
                                alt="">
                        </div>
                        <span class="qmenu-label">Profile</span>
                    </a>
                </li>
                <li class="qmenu-item">
                    <a href="{{ route('helps.list') }}"
                        class="qmenu-link {{ request()->routeIs('helps.list') ? 'active' : '' }}">
                        <i class="fa-solid fa-question-circle qmenu-icon"></i>
                        <span class="qmenu-label">Help</span>
                    </a>
                </li>
            </ul>
        </div> --}}
    </aside>
    <div class="qsidebar-overlay"></div>

    <main class="qmain">
        <header class="qheader">
            <button class="qbtn qbtn-icon d-lg-none d-flex qml-2" data-toggle="qsidebar-open"><i
                    class="icon fa-solid fa-bars"></i></button>
            <div class="qheader-title">
                <img width="32px" height="32px" src="{{ asset('assets/img/logo.ico') }}" alt="">
                <h4 class="qsubtitle qml-2">HRMS</h4>
            </div>
            {{-- @if (!request()->routeIs('password.request'))
                <div class="qtoggle m-0">
                    <a href="{{ route('lang.switch', 'kh') }}"
                        class="qtoggle-item {{ app()->getLocale() == 'kh' ? 'active' : '' }}">
                        KH
                    </a>

                    <a href="{{ route('lang.switch', 'en') }}"
                        class="qtoggle-item {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                        EN
                    </a>
                </div>
            @endif --}}
            <button class="qbtn qbtn-icon ms-1" data-toggle="theme" id="themeToggle">
                <i class="fa-solid fa-moon" id="themeIcon"></i>
            </button>
            @if (!request()->routeIs('password.request'))
                <form method="POST" action="{{ route('logout') }}" class="d-inline mx-1">
                    @csrf
                    <button type="submit" class="qbtn qbtn-icon" title="Logout">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            @endif
        </header>
        <section class="qcontent">
            <div class="container p-4 d-flex flex-column flex-grow-1" style="min-height: 0;">
                @yield('content')
            </div>
            @yield('modals')
        </section>
        <footer class="qfooter"></footer>
    </main>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.js"></script>
    <script src="{{ asset('dashboard/js/main.js') }}"></script>
    <script src="{{ asset('dashboard/qui/js/q.js') }}"></script>
    @yield('scripts')
</body>

</html>
