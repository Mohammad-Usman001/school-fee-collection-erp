<aside class="main-sidebar sidebar-dark-primary elevation-4 no-print">

    {{-- Brand Logo --}}
    <a href="{{ route('dashboard') }}" class="brand-link">
        <i class="fas fa-school ml-2"></i>
        <span class="brand-text font-weight-light ml-2">
            {{ config('app.name', 'Fees Manager') }}
        </span>
    </a>

    {{-- Sidebar --}}
    <div class="sidebar">

        {{-- Menu --}}
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li
                    class="nav-item has-treeview {{ request()->is('classes*') || request()->is('sections*') || request()->is('fee-heads*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->is('classes*') || request()->is('sections*') || request()->is('fee-heads*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-school"></i>
                        <p>
                            Masters
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('classes.index') }}"
                                class="nav-link {{ request()->is('classes*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Classes</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('sections.index') }}"
                                class="nav-link {{ request()->is('sections*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sections</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('fee-heads.index') }}"
                                class="nav-link {{ request()->is('fee-heads*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-coins"></i>
                                <p>Fee Heads</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('sessions.index') }}"
                        class="nav-link {{ request()->is('sessions*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>Academic Sessions</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('promotions.index') }}"
                        class="nav-link {{ request()->is('promotions*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-level-up-alt"></i>
                        <p>Student Promotion</p>
                    </a>
                </li>

                {{-- Students --}}
                <li class="nav-item">
                    <a href="{{ route('students.index') }}"
                        class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>Students</p>
                    </a>
                </li>
                @php
                    $feesMenuOpen = request()->is('fee-structures*') || request()->is('fees*');
                @endphp

                <li class="nav-item has-treeview {{ $feesMenuOpen ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $feesMenuOpen ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <p>
                            Fees Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('fee-structures.index') }}"
                                class="nav-link {{ request()->is('fee-structures*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Fee Structure</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('fees.index') }}"
                                class="nav-link {{ request()->is('fees*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Fee Collection</p>
                            </a>
                        </li>
                    </ul>
                </li>



                {{-- Reports --}}
                <li class="nav-item">
                    <a href="{{ route('reports.index') }}"
                        class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Reports</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ledger.index') }}"
                        class="nav-link {{ request()->is('ledger*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Student Ledger</p>
                    </a>
                </li>


                <li class="nav-header text-uppercase" style="opacity:.55; letter-spacing:1px; font-size:11px;">
                    SYSTEM
                </li>


                {{-- Backup --}}
                <li class="nav-item">
                    <a href="{{ route('backup.index') }}"
                        class="nav-link {{ request()->routeIs('backup.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-database"></i>
                        <p>Backup</p>
                    </a>
                </li>

                {{-- Settings --}}
                <li class="nav-item">
                    <a href="{{ route('settings.index') }}"
                        class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>Settings</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>
