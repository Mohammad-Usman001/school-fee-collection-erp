@extends('layouts.app')

@section('title','Add Fee Structure')
@section('page_title','Add Fee Structure')

@section('content')
<div class="card card-outline card-primary">

    <div class="card-header">
        <h3 class="card-title mb-0"><i class="fas fa-plus"></i> Add Fee Structure</h3>
    </div>

    <form action="{{ route('fee-structures.store') }}" method="POST">
        @csrf

        <div class="card-body">

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Session <span class="text-danger">*</span></label>
                    <select name="session" class="form-control" required>
                        @foreach($sessions as $ses)
                            <option value="{{ $ses->name }}" {{ $ses->name==$activeSession?'selected':'' }}>
                                {{ $ses->name }} {{ $ses->is_active?'(Active)':'' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Class <span class="text-danger">*</span></label>
                    <select name="class" class="form-control" required>
                        <option value="">Select Class</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->name }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr>

            <h6 class="mb-2"><i class="fas fa-layer-group"></i> Fee Heads (Add Amounts)</h6>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Select</th>
                            <th>Fee Head</th>
                            <th>Type</th>
                            <th width="180" class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($feeHeads as $i => $h)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="chkHead" data-index="{{ $i }}">
                            </td>
                            <td><strong>{{ $h->name }}</strong></td>
                            <td>
                                <span class="badge badge-{{ $h->frequency=='monthly'?'primary':'warning' }}">
                                    {{ strtoupper($h->frequency) }}
                                </span>
                            </td>
                            <td>
                                <input type="number" step="0.01" class="form-control amountBox"
                                       name="items[{{ $i }}][amount]" value="0" disabled>
                                <input type="hidden" name="items[{{ $i }}][fee_head_id]" value="{{ $h->id }}" disabled class="headIdBox">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

        <div class="card-footer text-right">
            <button class="btn btn-primary">
                <i class="fas fa-save"></i> Save Fee Structure
            </button>
        </div>
    </form>
</div>

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
                amount.value = amount.value || 0;
            }else{
                amount.disabled = true;
                headId.disabled = true;
                amount.value = 0;
            }
        });
    });
</script>
@endpush
@endsection
