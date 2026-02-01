<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'School Fees Management')</title>

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Optional page css --}}
    @stack('styles')

    <style>
        /* Smooth UI */
        body { font-size: 14px; }
        .content-wrapper { background: #f4f6f9; }
        .brand-link { font-weight: 600; }
        .nav-sidebar .nav-link.active {
            background: rgba(255,255,255,0.1) !important;
            color: #fff !important;
        }
        .table thead th { white-space: nowrap; }

        /* Print receipt clean */
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
            .content-wrapper { margin: 0 !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed text-sm">

<div class="wrapper">

    {{-- Navbar --}}
    @include('layouts.partials.navbar')

    {{-- Sidebar --}}
    @include('layouts.partials.sidebar')

    {{-- Content Wrapper --}}
    <div class="content-wrapper">

        {{-- Breadcrumb --}}
        @include('layouts.partials.breadcrumb')

        {{-- Flash Message --}}
        @include('layouts.partials.flash')

        {{-- Main content --}}
        <section class="content pb-5">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>

    {{-- Footer --}}
    @include('layouts.partials.footer')
</div>

{{-- Optional page scripts --}}
@stack('scripts')
</body>
</html>
