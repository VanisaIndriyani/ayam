<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bohrifarm') }}</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background: #f1f3f5;
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }

        .auth-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 40px 35px;
            max-width: 420px;
            width: 100%;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
        }

        .auth-logo img {
            width: 85px;
            height: auto;
        }
    </style>

    @stack('head')
</head>

<body>

    <div class="auth-wrapper">

        {{-- LOGO --}}
        <div class="text-center mb-4 auth-logo">
            <a href="/">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
            </a>
        </div>

        {{-- CARD --}}
        <div class="auth-card">
            {{ $slot }}
        </div>

    </div>

    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>
