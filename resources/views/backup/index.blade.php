@extends('layouts.app')

@section('title','Backup')
@section('page_title','Backup & Restore')

@section('breadcrumb')
    <li class="breadcrumb-item active">Backup</li>
@endsection

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
            <i class="fas fa-database mr-1"></i> Database Backups
        </h3>

        <div>
            <form action="{{ route('backup.create') }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-primary btn-sm" onclick="return confirm('Create new backup now?')">
                    <i class="fas fa-plus"></i> Create Backup
                </button>
            </form>

            <a href="{{ route('backup.restoreForm') }}" class="btn btn-warning btn-sm ml-2">
                <i class="fas fa-upload"></i> Restore Backup
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="alert alert-info">
            ✅ Backups are saved in <strong>storage/app/backups</strong> folder.
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Backup File</th>
                    <th>Size</th>
                    <th>Created At</th>
                    <th width="220">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($backups as $i => $b)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td><span class="badge badge-dark">{{ $b['file'] }}</span></td>
                        <td>{{ number_format($b['size'] / 1024, 2) }} KB</td>
                        <td>{{ date('d M Y h:i A', $b['time']) }}</td>
                        <td>
                            <a href="{{ route('backup.download', $b['file']) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-download"></i>
                            </a>

                            <form action="{{ route('backup.delete', $b['file']) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this backup?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            No backups found. Create your first backup.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
