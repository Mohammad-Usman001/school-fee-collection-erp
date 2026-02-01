@extends('layouts.app')

@section('title','Edit Fee Structure')
@section('page_title','Edit Fee Structure')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('fee-structures.index') }}">Fee Structure</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card card-outline card-info">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            <i class="fas fa-edit"></i> Edit Fee Structure
        </h3>

        <a href="{{ route('fee-structures.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <form action="{{ route('fee-structures.update', $feeStructure->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">

            {{-- ✅ Session + Class --}}
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Session <span class="text-danger">*</span></label>
                    <select name="session" class="form-control" required>
                        @foreach($sessions as $ses)
                            <option value="{{ $ses->name }}"
                                {{ old('session', $feeStructure->session) == $ses->name ? 'selected':'' }}>
                                {{ $ses->name }} {{ $ses->is_active?'(Active)':'' }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Session is required</small>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Class <span class="text-danger">*</span></label>
                    <select name="class" class="form-control" required>
                        @foreach($classes as $c)
                            <option value="{{ $c->name }}"
                                {{ old('class', $feeStructure->class) == $c->name ? 'selected':'' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Fee Structure is class wise</small>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Info</label>
                    <div class="alert alert-info py-2 mb-0">
                        <strong>Editing:</strong> {{ $feeStructure->session }} | {{ $feeStructure->class }}
                        <br>
                        <small class="text-muted">
                            All fee heads for this class/session will be updated together.
                        </small>
                    </div>
                </div>
            </div>

            <hr>

            {{-- ✅ Fee Heads table --}}
            <h6 class="mb-2">
                <i class="fas fa-layer-group"></i> Fee Heads Amount Setup
                <small class="text-muted">(tick only those you want in structure)</small>
            </h6>

            @php
                // existing head_ids list
                $existing = $structures->pluck('fee_head_id')->toArray();

                // map existing amounts head_id => amount
                $existingAmounts = $structures->pluck('amount','fee_head_id')->toArray();
            @endphp

            <div class="table-responsive">
                <table class="table table-bordered table-hover text-sm mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th width="80" class="text-center">Select</th>
                            <th>Fee Head</th>
                            <th width="140">Type</th>
                            <th width="200" class="text-right">Amount (₹)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($feeHeads as $i => $h)
                            @php
                                $checked = in_array($h->id, $existing);
                                $amountVal = $existingAmounts[$h->id] ?? 0;
                            @endphp
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" class="chkHead"
                                           data-index="{{ $i }}"
                                           {{ $checked ? 'checked':'' }}>
                                </td>

                                <td>
                                    <strong>{{ $h->name }}</strong>
                                    <br>
                                    <small class="text-muted">Head ID: {{ $h->id }}</small>
                                </td>

                                <td>
                                    <span class="badge badge-{{ $h->frequency=='monthly'?'primary':'warning' }}">
                                        {{ strtoupper($h->frequency) }}
                                    </span>
                                </td>

                                <td>
                                    <input type="number" step="0.01"
                                           class="form-control amountBox text-right"
                                           name="items[{{ $i }}][amount]"
                                           value="{{ old('items.'.$i.'.amount', $amountVal) }}"
                                           {{ $checked ? '' : 'disabled' }}>

                                    <input type="hidden"
                                           name="items[{{ $i }}][fee_head_id]"
                                           value="{{ $h->id }}"
                                           class="headIdBox"
                                           {{ $checked ? '' : 'disabled' }}>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

        {{-- footer --}}
        <div class="card-footer d-flex justify-content-between align-items-center">
            <a href="{{ route('fee-structures.index') }}" class="btn btn-light btn-outline-secondary">
                Cancel
            </a>

            <button class="btn btn-success">
                <i class="fas fa-save"></i> Update Fee Structure
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll(".chkHead").forEach(chk => {
        chk.addEventListener("change", function(){
            const row = this.closest("tr");
            const amount = row.querySelector(".amountBox");
            const headId = row.querySelector(".headIdBox");

            if(this.checked){
                amount.disabled = false;
                headId.disabled = false;
                if(parseFloat(amount.value || 0) <= 0){
                    amount.value = 0;
                }
            }else{
                amount.disabled = true;
                headId.disabled = true;
                amount.value = 0;
            }
        });
    });
</script>
@endpush
