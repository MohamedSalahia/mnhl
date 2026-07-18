<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('errors.page_not_found') | منهل</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #25B15D;
            --primary-dark: #1e8f4a;
            --primary-light: #4fd17f;
            --text-dark: #2d3748;
            --text-muted: #6c757d;
            --bg-light: #f8f9fa;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Tajawal', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .error-container {
            text-align: center;
            max-width: 600px;
            width: 100%;
        }

        /* Animated 404 Illustration */
        .illustration-wrapper {
            position: relative;
            width: 280px;
            height: 200px;
            margin: 0 auto 2rem;
        }

        .error-code {
            font-size: 8rem;
            font-weight: 800;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .digit {
            display: inline-block;
            color: var(--primary-color);
            animation: float 3s ease-in-out infinite;
            text-shadow: 0 10px 30px rgba(37, 177, 93, 0.3);
        }

        .digit:nth-child(1) {
            animation-delay: 0s;
        }

        .digit:nth-child(2) {
            animation-delay: 0.2s;
        }

        .digit:nth-child(3) {
            animation-delay: 0.4s;
        }

        .zero-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: float 3s ease-in-out infinite;
            animation-delay: 0.2s;
            box-shadow: 0 15px 40px rgba(37, 177, 93, 0.4);
            position: relative;
        }

        .zero-icon::before {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            border: 4px solid var(--white);
            border-radius: 50%;
            opacity: 0.8;
        }

        .zero-icon::after {
            content: '';
            position: absolute;
            width: 5px;
            height: 24px;
            background: var(--white);
            top: 66%;
            left: 61%;
            transform: rotate(-45deg);
            border-radius: 2px;
            transform-origin: top center;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-15px);
            }
        }

        /* Decorative elements */
        .decor-circle {
            position: absolute;
            border-radius: 50%;
            background: var(--primary-color);
            opacity: 0.1;
        }

        .decor-circle-1 {
            width: 60px;
            height: 60px;
            top: -20px;
            left: -30px;
            animation: pulse 4s ease-in-out infinite;
        }

        .decor-circle-2 {
            width: 40px;
            height: 40px;
            bottom: 20px;
            right: -20px;
            animation: pulse 4s ease-in-out infinite;
            animation-delay: 1s;
        }

        .decor-circle-3 {
            width: 20px;
            height: 20px;
            top: 40px;
            right: 10px;
            animation: pulse 4s ease-in-out infinite;
            animation-delay: 2s;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 0.1;
            }
            50% {
                transform: scale(1.2);
                opacity: 0.2;
            }
        }

        /* Content styling */
        .error-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
            line-height: 1.3;
        }

        .error-message {
            font-size: 1.1rem;
            color: var(--text-muted);
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        /* Button styling */
        .home-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: var(--white);
            text-decoration: none;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(37, 177, 93, 0.3);
        }

        .home-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(37, 177, 93, 0.4);
        }

        .home-btn svg {
            width: 20px;
            height: 20px;
            transition: transform 0.3s ease;
        }

        .home-btn:hover svg {
            transform: translateX(-4px);
        }

        [dir="ltr"] .home-btn:hover svg {
            transform: translateX(4px);
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .error-code {
                font-size: 6rem;
            }

            .zero-icon {
                width: 80px;
                height: 80px;
            }

            .zero-icon::before {
                width: 32px;
                height: 32px;
                border-width: 3px;
            }

            .zero-icon::after {
                width: 3px;
                height: 16px;
                top: 16px;
                right: 20px;
            }

            .illustration-wrapper {
                width: 220px;
                height: 160px;
            }

            .error-title {
                font-size: 1.5rem;
            }

            .error-message {
                font-size: 1rem;
            }

            .home-btn {
                padding: 0.875rem 2rem;
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>
<div class="error-container">
    <!-- Animated 404 Illustration -->
    <div class="illustration-wrapper">
        <div class="decor-circle decor-circle-1"></div>
        <div class="decor-circle decor-circle-2"></div>
        <div class="decor-circle decor-circle-3"></div>

        <div class="error-code">
            <span class="digit">4</span>
            <span class="zero-icon"></span>
            <span class="digit">4</span>
        </div>
    </div>

    <!-- Error Content -->
    <h1 class="error-title">@lang('site.page_not_found')</h1>
    <p class="error-message">@lang('site.page_not_found_message')</p>

    <!-- Back to Home Button -->
    <a href="{{ url('/') }}" class="home-btn">
        @if(app()->getLocale() == 'ar')
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        @else
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
            </svg>
        @endif
        @lang('site.back_home')
    </a>
</div>
</body>
</html>
