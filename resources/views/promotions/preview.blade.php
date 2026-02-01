@extends('layouts.app')

@section('title','Promotion Preview')
@section('page_title','Promotion Preview')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('promotions.index') }}">Promotion</a></li>
    <li class="breadcrumb-item active">Preview</li>
@endsection

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">
            <i class="fas fa-eye mr-1"></i> Promotion Preview
        </h3>
        <a href="{{ route('promotions.index') }}" class="btn btn-secondary btn-sm">Back</a>
    </div>

    <div class="card-body">

        <div class="alert alert-info">
            <strong>Total Students Found:</strong> {{ $students->count() }}
        </div>

        <form method="POST" action="{{ route('promotions.confirm') }}"
              onsubmit="return confirm('Confirm promotion for selected students?')">
            @csrf

            {{-- hidden promotion data --}}
            <input type="hidden" name="to_class" value="{{ $promotionData['to_class'] }}">
            <input type="hidden" name="to_section" value="{{ $promotionData['to_section'] ?? '' }}">
            <input type="hidden" name="to_session" value="{{ $promotionData['to_session'] }}">

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th width="60">
                                <input type="checkbox" id="selectAll" checked>
                            </th>
                            <th>Student</th>
                            <th>Unique ID</th>
                            <th>Current Class</th>
                            <th>Current Section</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $st)
                        <tr>
                            <td>
                                <input type="checkbox" name="student_ids[]"
                                       value="{{ $st->id }}" class="rowCheck" checked>
                            </td>
                            <td>{{ $st->name }}</td>
                            <td>{{ $st->unique_id }}</td>
                            <td>{{ $st->class }}</td>
                            <td>{{ $st->section }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-right mt-3">
                <button class="btn btn-primary">
                    <i class="fas fa-check"></i> Confirm Promotion
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('selectAll').addEventListener('change', function(){
        document.querySelectorAll('.rowCheck').forEach(chk => chk.checked = this.checked);
    });
</script>
@endpush
