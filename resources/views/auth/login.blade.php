<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Anchor Development and Construction Company - Login</title>

    <link href="{{ asset('assets/css/modern.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/classic.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/dark.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/light.css') }}" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #e0eafc, #cfdef3);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .login-card {
            background: #fff;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 400px;
        }

        .login-btn {
            background-color: #2b1d61;
            border-color: #2b1d61;
            color: #fff;
        }

        .login-btn:hover {
            background-color: #231650;
        }

        .login-image {
            width: 100%;
            height: 100vh;
            object-fit: cover;
            border-top-right-radius: 1rem;
            border-bottom-right-radius: 1rem;
        }

        .logo {
            max-width: 180px;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <div class="container-fluid login-container">
        <div class="row w-100">
            <div class="col-lg-6 d-flex align-items-center justify-content-center">
                <div class="login-card">
                    <div class="text-center">
                        <img src="{{ asset('assets/img/logo/adcc.png') }}" alt="Logo" class="logo">
                        <h4 class="mb-1">Welcome Back!</h4>
                        <p class="text-muted mb-4">Sign in to continue to ADCC</p>
                    </div>

                    <form method="POST" action="{{ route('login.form') }}">
                        {{-- <form method="POST" action="{{ route('login.submit') }}"> --}}
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" class="form-control form-control-lg" type="email" name="email"
                                placeholder="Enter your email" required>
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" class="form-control form-control-lg" type="password" name="password"
                                placeholder="Enter your password" required>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn login-btn btn-lg">Sign In</button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <small class="text-muted">© <script>document.write(new Date().getFullYear())</script>
                            <a href="#">Anchor Development and Construction Company</a><br>
                            Developed by
                            <a href="https://synergyintegratedsolutions.pk/">Synergy Integrated Solutions</a>
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center bg-light p-0" >
            <img src="{{ asset('assets/img/11.jpg') }}" alt="Login Background" class="img-fluid rounded-end" style="max-height: 100vh; object-fit: cover;">
        </div>

        </div>
    </div>

    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>

</html>
