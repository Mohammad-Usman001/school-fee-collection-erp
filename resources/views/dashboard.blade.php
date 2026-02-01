@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
    <style>
        .progress {
            background-color: #e9ecef;
        }

        .card-body {
            flex: 1 1 auto;
            min-height: 1px;
            padding: 1.25rem;
            align-content: center;
        }
    </style>
    {{-- ================= SESSION OVERVIEW ================= --}}
    <div class="row mb-3">

        <div class="col-lg-8">
            <div class="card card-outline card-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-2">
                            <i class="fas fa-hourglass-half text-success mr-1"></i>
                            Academic Session Progress
                        </h5>
                        <span class="badge badge-success px-3 py-2">
                            {{ $session }} · {{ $sessionProgress }}%
                        </span>
                    </div>

                    <div class="progress mt-2" style="height:22px;">
                        <div class="progress-bar bg-success progress-bar-striped" style="width: {{ $sessionProgress }}%">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-2 text-muted small">
                        <span>April</span>
                        <span>March</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- SESSION SELECT --}}
        <div class="col-lg-4">
            <div class="card card-outline card-primary h-100">
                <div class="card-body">
                    <label class="text-muted mb-1">Select Session</label>
                    <form method="GET">
                        <select name="session" class="form-control" onchange="this.form.submit()">
                            @foreach ($sessions as $ses)
                                <option value="{{ $ses->name }}" {{ $session == $ses->name ? 'selected' : '' }}>
                                    {{ $ses->name }} {{ $ses->is_active ? '(Active)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    <div class="alert alert-success mt-3 mb-0 py-2">
                        <i class="fas fa-check-circle"></i>
                        Current Session: <strong>{{ $session }}</strong>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ================= KPI STRIP ================= --}}
    <div class="row">

        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalStudents }}</h3>
                    <p>Total Students</p>
                </div>
                <div class="icon"><i class="fas fa-user-graduate"></i></div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>₹{{ number_format($totalInvoiced, 2) }}</h3>
                    <p>Total Invoiced</p>
                </div>
                <div class="icon"><i class="fas fa-file-invoice"></i></div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>₹{{ number_format($totalCollected, 2) }}</h3>
                    <p>Total Collected</p>
                </div>
                <div class="icon"><i class="fas fa-wallet"></i></div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>₹{{ number_format($totalDue, 2) }}</h3>
                    <p>Total Due</p>
                </div>
                <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
            </div>
        </div>

    </div>
    {{-- ✅ Today / Month Highlights --}} <div class="row">
        <div class="col-md-6">
            <div class="info-box bg-light"> <span class="info-box-icon bg-success"><i
                        class="fas fa-calendar-day"></i></span>
                <div class="info-box-content"> <span class="info-box-text">Today Collection</span> <span
                        class="info-box-number text-success">₹{{ number_format($todayCollection, 2) }}</span> <span
                        class="text-muted">Date: {{ date('d M Y') }}</span> </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-box bg-light"> <span class="info-box-icon bg-primary"><i
                        class="fas fa-calendar-alt"></i></span>
                <div class="info-box-content"> <span class="info-box-text">This Month Collection</span> <span
                        class="info-box-number text-primary">₹{{ number_format($monthCollection, 2) }}</span> <span
                        class="text-muted">Month: {{ date('M Y') }}</span> </div>
            </div>
        </div>
    </div> {{-- ✅ Quick Actions --}} <div class="card card-outline card-secondary mb-3">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-bolt mr-1"></i> Quick Actions</h3>
        </div>
        <div class="card-body"> <a href="{{ route('students.create') }}" class="btn btn-info mr-2 mb-2"> <i
                    class="fas fa-user-plus"></i> Add Student </a> <a href="{{ route('reports.index') }}"
                class="btn btn-primary mr-2 mb-2"> <i class="fas fa-chart-bar"></i> Reports </a>
                 <a href="{{ route('fees.index') }}" class="btn btn-warning mb-2"> <i class="nav-icon fas fa-money-bill-wave"></i> Fee Collection
            </a> <a href="{{ route('ledger.index', ['session' => $session]) }}" class="btn btn-dark ml-2 mb-2"> <i
                    class="fas fa-book"></i> Student Ledger </a> </div>
    </div>
    
    {{-- ================= FEE HEALTH ================= --}}
    <div class="card card-outline card-warning mb-3">
        <div class="card-body">
            <h6 class="mb-2">
                <i class="fas fa-heartbeat mr-1"></i>
                Fee Collection Health
            </h6>

            @php
                $health = $totalInvoiced > 0 ? round(($totalCollected / $totalInvoiced) * 100) : 0;
            @endphp

            <div class="progress" style="height:20px;">
                <div class="progress-bar bg-{{ $health >= 80 ? 'success' : ($health >= 50 ? 'warning' : 'danger') }}"
                    style="width: {{ $health }}%">
                    {{ $health }}%
                </div>
            </div>

            <small class="text-muted">
                Collected vs Invoiced
            </small>
        </div>
    </div>

    {{-- ================= CLASS / SECTION ANALYTICS ================= --}}
    <div class="card card-outline card-info mb-3">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-users mr-1"></i>
                Class / Section Strength
            </h3>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Class</th>
                        <th>Section</th>
                        <th class="text-right">Students</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classSectionStats as $row)
                        <tr>
                            <td>{{ $row->class }}</td>
                            <td>{{ $row->section === 'NO_SECTION' ? '-' : $row->section }}</td>
                            <td class="text-right">
                                <span class="badge badge-primary">{{ $row->total }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">No data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================= CHART + RECENT ================= --}}
    <div class="row">

        <div class="col-lg-7">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Monthly Collection Trend
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="140"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card card-outline card-danger">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        High Due Students
                    </h3>
                </div>

                <table class="table table-sm table-bordered mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Student</th>
                            <th>Class</th>
                            <th class="text-right">Due</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topDues as $d)
                            <tr>
                                <td>{{ $d->student->name ?? '-' }}</td>
                                <td>{{ $d->student->class ?? '-' }}</td>
                                <td class="text-danger text-right">
                                    ₹{{ number_format($d->due_amount, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    No risk students 🎉
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('monthlyChart'), {
            type: 'bar',
            data: {
                labels: @json($monthlyChart->pluck('month')),
                datasets: [{
                    label: 'Collection',
                    data: @json($monthlyChart->pluck('total')),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endpush
