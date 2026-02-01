@extends('layouts.app')

@section('title','Restore Backup')
@section('page_title','Restore Backup')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backup.index') }}">Backup</a></li>
    <li class="breadcrumb-item active">Restore</li>
@endsection

@section('content')
<div class="card card-outline card-warning">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
            <i class="fas fa-upload mr-1"></i> Restore Database
        </h3>

        <a href="{{ route('backup.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <form action="{{ route('backup.restore') }}" method="POST" enctype="multipart/form-data"
          onsubmit="return confirm('Restore backup? This will replace your current database!')">
        @csrf

        <div class="card-body">
            <div class="alert alert-danger">
                ⚠️ Restoring backup will replace current data. A safety backup will be created automatically before restore.
            </div>

            <div class="form-group">
                <label>Select Backup File (.sqlite) <span class="text-danger">*</span></label>
                <input type="file" name="backup_file" class="form-control" required accept=".sqlite,.db">
            </div>
        </div>

        <div class="card-footer text-right">
            <button class="btn btn-warning">
                <i class="fas fa-sync"></i> Restore Now
            </button>
        </div>
    </form>
</div>
@endsection
