@extends('layouts.app')

@section('title', 'Student Promotion')
@section('page_title', 'Student Promotion')

@section('breadcrumb')
    <li class="breadcrumb-item active">Promotion</li>
@endsection

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-level-up-alt mr-1"></i> Promote Students (Bulk)</h3>
        </div>


        <form method="POST" action="{{ route('promotions.preview') }}">

            @csrf

            <div class="card-body">

                <div class="alert alert-info">
                    ✅ Promote students from one class to another in 1 click.
                </div>

                <div class="row">

                    {{-- From Class --}}
                    <div class="col-md-3 mb-3">
                        <label>From Class <span class="text-danger">*</span></label>
                        <select name="from_class" class="form-control" required>
                            <option value="">Select</option>
                            @foreach ($classes as $c)
                                <option value="{{ $c->name }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- From Section --}}
                    <div class="col-md-3 mb-3">
                        <label>From Section</label>
                        <select name="from_section" class="form-control">
                            <option value="">All Sections</option>
                            @foreach ($sections as $s)
                                <option value="{{ $s->name }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- To Class --}}
                    <div class="col-md-3 mb-3">
                        <label>To Class <span class="text-danger">*</span></label>
                        <select name="to_class" class="form-control" required>
                            <option value="">Select</option>
                            @foreach ($classes as $c)
                                <option value="{{ $c->name }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- To Section --}}
                    <div class="col-md-3 mb-3">
                        <label>To Section</label>
                        <select name="to_section" class="form-control">
                            <option value="">Keep Same</option>
                            @foreach ($sections as $s)
                                <option value="{{ $s->name }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- To Session --}}
                    <div class="col-md-4 mb-3">
                        <label>To Session <span class="text-danger">*</span></label>
                        <select name="to_session" class="form-control" required>
                            <option value="">Select</option>
                            @foreach ($sessions as $ses)
                                <option value="{{ $ses->name }}" {{ $ses->is_active ? 'selected' : '' }}>
                                    {{ $ses->name }} {{ $ses->is_active ? '(Active)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>

            <div class="card-footer text-right">
                <button class="btn btn-primary">
                    <i class="fas fa-check"></i> Promote Now
                </button>
            </div>

        </form>
    </div>
@endsection
