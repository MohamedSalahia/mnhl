@extends('layouts.auth')

@section('content')
    <div class="login-container">
        <div class="login-wrapper">
            <div class="login-card">
                <!-- Logo/Brand Section -->
                <div class="login-header">
                    <div class="logo-wrapper">
                        <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="30" cy="30" r="30" fill="url(#gradient1)"/>
                            <path d="M30 15L38 25H34V40H26V25H22L30 15Z" fill="white"/>
                            <defs>
                                <linearGradient id="gradient1" x1="0" y1="0" x2="60" y2="60" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#10b981"/>
                                    <stop offset="1" stop-color="#059669"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <h1 class="login-title">{{ __('auth.welcome_back') }}</h1>
                    <p class="login-subtitle">{{ __('auth.sign_in_to_continue') }}</p>
                </div>

                <!-- Login Form -->
                <div class="login-body">
                    <form method="POST" action="{{ route('login') }}" class="login-form ajax-form">
                        @csrf

                        <!-- Email Field -->
                        <div class="form-group">
                            <label for="email" class="form-label">
                                {{ __('users.email_address') }}
                            </label>
                            <div class="input-wrapper">
                                <input
                                    id="email"
                                    type="email"
                                    class="form-control"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autocomplete="email"
                                    autofocus
                                    placeholder="{{ __('auth.email_placeholder') }}"
                                >
                                <span class="input-icon">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2.5 6.66667L10 11.6667L17.5 6.66667M3.33333 15H16.6667C17.5871 15 18.3333 14.2538 18.3333 13.3333V6.66667C18.3333 5.74619 17.5871 5 16.6667 5H3.33333C2.41286 5 1.66667 5.74619 1.66667 6.66667V13.3333C1.66667 14.2538 2.41286 15 3.33333 15Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="form-group">
                            <label for="password" class="form-label">
                                {{ __('users.password') }}
                            </label>
                            <div class="input-wrapper">
                                <input
                                    id="password"
                                    type="password"
                                    class="form-control"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="{{ __('auth.password_placeholder') }}"
                                >
                                <span class="input-icon">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15.8333 9.16667V5.83333C15.8333 3.53215 13.9681 1.66667 11.6667 1.66667H8.33333C6.03185 1.66667 4.16667 3.53215 4.16667 5.83333V9.16667M5.83333 18.3333H14.1667C15.5474 18.3333 16.6667 17.214 16.6667 15.8333V11.6667C16.6667 10.286 15.5474 9.16667 14.1667 9.16667H5.83333C4.45262 9.16667 3.33333 10.286 3.33333 11.6667V15.8333C3.33333 17.214 4.45262 18.3333 5.83333 18.3333Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                                <button type="button" class="password-toggle" onclick="togglePassword()" style="outline: none;">
                                    <svg id="eye-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1.66667 10C1.66667 10 4.16667 4.16667 10 4.16667C15.8333 4.16667 18.3333 10 18.3333 10C18.3333 10 15.8333 15.8333 10 15.8333C4.16667 15.8333 1.66667 10 1.66667 10Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <circle cx="10" cy="10" r="2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="form-options">
                            <div class="remember-me">
                                <input type="checkbox" id="remember" name="remember" class="custom-checkbox">
                                <label for="remember" class="checkbox-label">
                                    {{ __('site.remember_me') }}
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group mb-0">
                            <button type="submit" class="btn-login">
                                <span>{{ __('site.login') }}</span>
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <!-- Decorative Background Elements -->
            <div class="bg-decoration">
                <div class="circle circle-1"></div>
                <div class="circle circle-2"></div>
                <div class="circle circle-3"></div>
                <div class="gradient-blob blob-1"></div>
                <div class="gradient-blob blob-2"></div>
            </div>
        </div>
    </div>

    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 450px;
        }

        .login-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08), 0 2px 8px rgba(0, 0, 0, 0.04);
            border: 1px solid #e5e7eb;
            padding: 48px 40px;
            animation: slideUp 0.6s ease-out;
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #10b981 0%, #34d399 50%, #059669 100%);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-wrapper {
            margin-bottom: 24px;
            display: inline-block;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .login-title {
            font-size: 32px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .login-subtitle {
            font-size: 15px;
            color: #718096;
            margin: 0;
        }

        .login-body {
            width: 100%;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: flex;
            align-items: center;
            font-size: 14px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
        }


        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px 14px 48px;
            font-size: 15px;
            border: 2px solid #e2e8f0;
            border-radius: 5px;
            background: #f7fafc;
            transition: all 0.3s ease;
            color: #2d3748;
        }

        .form-control:focus {
            outline: none;
            border-color: #10b981;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        .form-control::placeholder {
            color: #a0aec0;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            color: #718096;
            pointer-events: none;
            display: flex;
            align-items: center;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            background: none;
            border: none;
            color: #718096;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            transition: color 0.2s ease;
        }

        .password-toggle:hover {
            color: #10b981;
        }

        .form-control.is-invalid {
            border-color: #e53e3e;
            background: #fff5f5;
        }

        .invalid-feedback {
            display: block;
            margin-top: 8px;
            font-size: 13px;
            color: #e53e3e;
            font-weight: 500;
        }

        .form-options {
            display: flex;
            align-items: center;
            margin-bottom: 32px;
        }

        .remember-me {
            display: flex;
            align-items: center;
        }

        .custom-checkbox {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            cursor: pointer;
            accent-color: #10b981;
        }

        .checkbox-label {
            font-size: 14px;
            color: #4a5568;
            cursor: pointer;
            user-select: none;
        }

        .forgot-password {
            font-size: 14px;
            color: #10b981;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .forgot-password:hover {
            color: #059669;
            text-decoration: none;
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .register-link {
            text-align: center;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #e2e8f0;
        }

        .register-link p {
            margin: 0;
            font-size: 14px;
            color: #718096;
        }

        .register-link a {
            color: #10b981;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .register-link a:hover {
            color: #059669;
            text-decoration: none;
        }

        .bg-decoration {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            border-radius: 24px;
            z-index: 0;
            pointer-events: none;
        }

        .circle {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.08) 100%);
            animation: float 20s infinite ease-in-out;
        }

        .circle-1 {
            width: 300px;
            height: 300px;
            top: -150px;
            right: -150px;
            animation-delay: 0s;
        }

        .circle-2 {
            width: 200px;
            height: 200px;
            bottom: -100px;
            left: -100px;
            animation-delay: 5s;
        }

        .circle-3 {
            width: 150px;
            height: 150px;
            top: 50%;
            left: 10%;
            animation-delay: 10s;
        }

        .gradient-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            animation: float 25s infinite ease-in-out;
        }

        .blob-1 {
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            top: -200px;
            right: -200px;
            animation-delay: 0s;
        }

        .blob-2 {
            width: 350px;
            height: 350px;
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            bottom: -175px;
            left: -175px;
            animation-delay: 8s;
        }

        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) scale(1);
            }
            33% {
                transform: translate(30px, -30px) scale(1.1);
            }
            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            .login-card {
                padding: 32px 24px;
                border-radius: 20px;
            }

            .login-title {
                font-size: 28px;
            }

            .form-options {
                align-items: flex-start;
            }

            [dir="rtl"] .form-options {
                align-items: flex-end;
            }
        }

        /* RTL Support */
        [dir="rtl"] {
            font-family: 'Cairo', sans-serif;
        }

        [dir="rtl"] .login-container,
        [dir="rtl"] .login-card,
        [dir="rtl"] .login-title,
        [dir="rtl"] .login-subtitle,
        [dir="rtl"] .form-label,
        [dir="rtl"] .form-control,
        [dir="rtl"] .checkbox-label,
        [dir="rtl"] .forgot-password,
        [dir="rtl"] .btn-login {
            font-family: 'Cairo', sans-serif;
        }


        [dir="rtl"] .form-control {
            padding: 14px 48px 14px 16px;
            text-align: right;
        }

        [dir="rtl"] .input-icon {
            left: auto;
            right: 16px;
        }

        [dir="rtl"] .password-toggle {
            right: auto;
            left: 16px;
        }

        [dir="rtl"] .custom-checkbox {
            margin-right: 0;
            margin-left: 8px;
        }


        [dir="rtl"] .btn-login {
            flex-direction: row-reverse;
        }

        [dir="rtl"] .btn-login svg {
            transform: scaleX(-1);
        }

        [dir="rtl"] .login-card::before {
            background: linear-gradient(270deg, #10b981 0%, #34d399 50%, #059669 100%);
        }

        [dir="rtl"] .form-control::placeholder {
            text-align: right;
        }
    </style>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
            <path d="M1.66667 10C1.66667 10 4.16667 4.16667 10 4.16667C15.8333 4.16667 18.3333 10 18.3333 10C18.3333 10 15.8333 15.8333 10 15.8333C4.16667 15.8333 1.66667 10 1.66667 10Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M12.5 7.5L7.5 12.5M7.5 7.5L12.5 12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
            <path d="M1.66667 10C1.66667 10 4.16667 4.16667 10 4.16667C15.8333 4.16667 18.3333 10 18.3333 10C18.3333 10 15.8333 15.8333 10 15.8333C4.16667 15.8333 1.66667 10 1.66667 10Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <circle cx="10" cy="10" r="2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        `;
            }
        }
    </script>
@endsection
