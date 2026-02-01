@extends('layouts.app')

@section('title','Reports')
@section('page_title','Reports Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Reports</li>
@endsection

@section('content')

{{-- ================= SUMMARY CARDS ================= --}}
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
        <div class="small-box bg-success">
            <div class="inner">
                <h3>₹{{ number_format($totalReceived,2) }}</h3>
                <p>Total Fee Collected</p>
            </div>
            <div class="icon"><i class="fas fa-wallet"></i></div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>₹{{ number_format($totalDue,2) }}</h3>
                <p>Total Outstanding Due</p>
            </div>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>₹{{ number_format($monthReceived,2) }}</h3>
                <p>This Month Collection ({{ $thisMonth }})</p>
            </div>
            <div class="icon"><i class="fas fa-calendar-check"></i></div>
        </div>
    </div>

</div>

{{-- ================= QUICK REPORT LINKS ================= --}}
<div class="card card-outline card-primary mt-3">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-chart-bar mr-1"></i> Quick Reports
        </h3>
    </div>

    <div class="card-body">
        <a href="{{ route('reports.monthly') }}" class="btn btn-primary mr-2">
            <i class="fas fa-calendar"></i> Monthly Collection
        </a>

        <a href="{{ route('reports.classWise') }}" class="btn btn-info mr-2">
            <i class="fas fa-layer-group"></i> Class Wise Report
        </a>

        <a href="{{ route('reports.dues') }}" class="btn btn-warning">
            <i class="fas fa-exclamation-circle"></i> Due / Pending Fees
        </a>
    </div>
</div>

@endsection
