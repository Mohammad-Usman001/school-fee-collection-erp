<nav class="main-header navbar navbar-expand navbar-white navbar-light no-print">

    {{-- ✅ LEFT: Sidebar Toggle + Brand --}}
    <ul class="navbar-nav align-items-center">
        {{-- <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li> --}}

        <li class="nav-item d-none d-md-inline-block">
            <a href="{{ route('dashboard') }}" class="nav-link font-weight-bold">
                <i class="fas fa-school mr-1 text-primary"></i> Offline School Fees App
            </a>
        </li>
    </ul>

    {{-- ✅ CENTER: Quick Search --}}
    <form class="form-inline mx-auto d-none d-lg-flex" action="{{ route('students.index') }}" method="GET">
        <div class="input-group input-group-sm" style="width: 420px;">
            <input class="form-control form-control-navbar"
                   type="search"
                   name="search"
                   placeholder="Quick Search Student (Name / ID / Phone)"
                   aria-label="Search"
                   value="{{ request('search') }}">

            <div class="input-group-append">
                <button class="btn btn-navbar btn-primary" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>

    {{-- ✅ RIGHT --}}
    <ul class="navbar-nav ml-auto align-items-center">

        {{-- ✅ Current Session Badge --}}
        <li class="nav-item d-none d-md-inline-block">
            <span class="badge badge-success px-3 py-2" style="font-size: 12px;">
                <i class="fas fa-check-circle mr-1"></i>
                Session: {{ active_session_name() ?? 'N/A' }}
            </span>
        </li>

        {{-- ✅ Live Clock --}}
        <li class="nav-item d-none d-md-inline-block ml-2">
            <span class="badge badge-light px-3 py-2" style="font-size:12px; border:1px solid #e8edf5;">
                <i class="far fa-clock mr-1 text-muted"></i>
                <span id="liveClock">--:--</span>
            </span>
        </li>

        {{-- ✅ Notifications (Future) --}}
        <li class="nav-item ml-2">
            <a class="nav-link" href="#" title="Notifications">
                <i class="far fa-bell"></i>
                <span class="badge badge-danger navbar-badge">0</span>
            </a>
        </li>

        {{-- Fullscreen --}}
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button" title="Fullscreen">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        {{-- ✅ User Dropdown --}}
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown" href="#">
                <span class="mr-2 d-none d-md-inline text-dark font-weight-bold">
                    {{ auth()->user()->name ?? 'User' }}
                </span>

                {{-- Avatar --}}
                <span class="badge badge-primary rounded-circle"
                      style="width:34px;height:34px;display:flex;align-items:center;justify-content:center;font-size:13px;">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </span>
            </a>

            <div class="dropdown-menu dropdown-menu-right shadow" style="border-radius:14px; overflow:hidden;">
                <div class="dropdown-header text-center bg-light">
                    <strong>{{ auth()->user()->name ?? 'User' }}</strong><br>
                    <small class="text-muted">{{ auth()->user()->email ?? '' }}</small>
                </div>

                <div class="dropdown-divider"></div>

                <a href="#" class="dropdown-item">
                    <i class="fas fa-user mr-2 text-primary"></i> Profile
                </a>

                <a href="{{ route('settings.index') }}" class="dropdown-item">
                    <i class="fas fa-cog mr-2 text-warning"></i> Settings
                </a>

                <div class="dropdown-divider"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="dropdown-item text-danger" type="submit">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </li>

    </ul>
</nav>

{{-- ✅ Live Clock Script --}}
@push('scripts')
<script>
    function updateClock(){
        const now = new Date();
        const options = { hour: '2-digit', minute: '2-digit', second:'2-digit' };
        document.getElementById('liveClock').innerText = now.toLocaleTimeString('en-IN', options);
    }
    updateClock();
    setInterval(updateClock, 1000);
</script>
@endpush
