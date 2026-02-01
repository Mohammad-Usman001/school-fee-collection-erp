<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Auth') | {{ config('app.name') }}</title>

    {{-- ✅ Load same CSS/JS as AdminLTE (Vite build) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body{
            min-height: 100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            background:#f4f6f9;
            padding:20px;
        }
        .auth-box{
            width: 420px;
            max-width: 100%;
        }
        .brand-logo{
            display:flex;
            justify-content:center;
            margin-bottom:15px;
        }
        .brand-logo img{
            width:80px;
            height:auto;
        }
    </style>

    @stack('styles')
</head>
<body>

    @yield('content')

    @stack('scripts')
</body>
</html>
