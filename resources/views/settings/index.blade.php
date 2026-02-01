@extends('layouts.app')

@section('title','Settings')
@section('page_title','Settings')

@section('breadcrumb')
    <li class="breadcrumb-item active">Settings</li>
@endsection

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-cog mr-1"></i> Application Settings
        </h3>
    </div>

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card-body">

            {{-- SCHOOL INFO --}}
            <h5 class="mb-3 text-primary"><i class="fas fa-school"></i> School Information</h5>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>School Name <span class="text-danger">*</span></label>
                    <input type="text" name="school_name" class="form-control"
                           value="{{ old('school_name', $setting->school_name) }}" required>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Phone</label>
                    <input type="text" name="school_phone" class="form-control"
                           value="{{ old('school_phone', $setting->school_phone) }}">
                </div>

                <div class="col-md-3 mb-3">
                    <label>Email</label>
                    <input type="email" name="school_email" class="form-control"
                           value="{{ old('school_email', $setting->school_email) }}">
                </div>

                <div class="col-md-12 mb-3">
                    <label>Address</label>
                    <textarea name="school_address" rows="2" class="form-control">{{ old('school_address', $setting->school_address) }}</textarea>
                </div>
            </div>

            <hr>

            {{-- LOGO --}}
            <h5 class="mb-3 text-primary"><i class="fas fa-image"></i> School Logo</h5>
            <div class="row align-items-center">
                <div class="col-md-8 mb-3">
                    <label>Upload Logo (PNG/JPG)</label>
                    <input type="file" name="school_logo" class="form-control" accept=".png,.jpg,.jpeg">
                    <small class="text-muted">Max size: 2MB</small>
                </div>

                <div class="col-md-4 mb-3 text-center">
                    @if($setting->school_logo)
                        <img src="{{ asset($setting->school_logo) }}" style="max-width:120px;" class="img-thumbnail">
                    @else
                        <p class="text-muted">No logo uploaded</p>
                    @endif
                </div>
            </div>

            <hr>

            {{-- RECEIPT SETTINGS --}}
            <h5 class="mb-3 text-primary"><i class="fas fa-receipt"></i> Receipt Settings</h5>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Receipt Prefix <span class="text-danger">*</span></label>
                    <input type="text" name="receipt_prefix" class="form-control"
                           value="{{ old('receipt_prefix', $setting->receipt_prefix) }}" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Currency Symbol <span class="text-danger">*</span></label>
                    <input type="text" name="currency_symbol" class="form-control"
                           value="{{ old('currency_symbol', $setting->currency_symbol) }}" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Session Year</label>
                    <input type="text" name="session_year" class="form-control"
                           placeholder="e.g 2025-26"
                           value="{{ old('session_year', $setting->session_year) }}">
                </div>

                <div class="col-md-12 mb-3">
                    <label>Receipt Footer Note</label>
                    <textarea name="receipt_footer" rows="2" class="form-control">{{ old('receipt_footer', $setting->receipt_footer) }}</textarea>
                </div>
            </div>

            <hr>

            {{-- BACKUP SETTINGS --}}
            <h5 class="mb-3 text-primary"><i class="fas fa-database"></i> Backup Settings</h5>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Backup Retention (Days)</label>
                    <input type="number" name="backup_retention" class="form-control"
                           value="{{ old('backup_retention', $setting->backup_retention) }}">
                    <small class="text-muted">How many backups to keep. Old backups can be auto deleted.</small>
                </div>
            </div>

        </div>

        <div class="card-footer text-right">
            <button class="btn btn-primary">
                <i class="fas fa-save"></i> Save Settings
            </button>
        </div>
    </form>
</div>
@endsection
