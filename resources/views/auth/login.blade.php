<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>HRMS</title>

    <!-- Fonts -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('dashboard/css/main.css') }}" rel="stylesheet">
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
    <link href="{{ asset('dashboard/qui/css/one-ui.css') }}" rel="stylesheet">

</head>

<body class="d-flex align-items-center">

    <!-- Main Content -->
    <div class="container vh-100 d-flex flex-column p-0">
        <div class="row flex-grow-1 w-100 m-0 align-items-center justify-content-center">
            <!-- Left Side - Wallpaper -->
            {{-- <div class="col-md-5 d-none d-md-block p-0">
                <div class="image-container">
                    <img src="{{ asset('assets/img/design_login.png') }}" class="img-fluid" alt="Login Design" style="backdrop-filter: blur(8px); opacity: 0.7;">
                </div>
            </div> --}}

            <!-- Right Side - Login Form -->
            <div class="col-md-5 d-flex flex-column justify-content-center align-items-center">
                <div class="text-center mb-4">
                    <h2 class="fw-bolder qtext-red">HRMS</h2>
                    <p class="bdark-blue">Secure login to your account</p>
                </div>

                <div class="p-4 qcard w-75 w-sm-100">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="row">
                            <x-form.input name="username" label="Username" required col="col-12" />
                            <x-form.input name="password" label="Password" type="password" required col="col-12" />
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="show_password"
                                        id="show_password" {{ old('show_password') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="show_password">Show Password</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <hr>
                                <button type="submit" class="qbtn qbtn-blue mt-3 w-100">
                                    Login
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="text-center mt-4">
                    <p class="bdark-blue">
                        <strong class="fw-bold"> HRMS</strong> <br>
                        <strong> Licensed to </strong>: BNKC (Cambodia) Microfinance PLC. | License Expiry Date:
                        Unlimited
                    </p>
                </div>
            </div>
        </div>

        <footer class="text-center bdark-blue small py-2">
            &copy; 2025 - Department HR & Admin HRMS. All rights reserved.
        </footer>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.js"></script>
    <script src="{{ asset('dashboard/js/main.js') }}"></script>
    <script src="{{ asset('dashboard/qui/js/q.js') }}"></script>
    @yield('scripts')
    <script>
        initPasswordToggle();
    </script>

</body>

</html>
