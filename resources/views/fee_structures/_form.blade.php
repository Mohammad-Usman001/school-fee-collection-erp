<div class="row">

    <div class="col-md-6 mb-3">
        <label>Class <span class="text-danger">*</span></label>
        <select name="class" class="form-control" required>
            <option value="">Select Class</option>
            @foreach ($classes as $c)
                <option value="{{ $c->name }}"
                    {{ old('class', $feeStructure->class ?? '') == $c->name ? 'selected' : '' }}>
                    {{ $c->name }}
                </option>
            @endforeach
        </select>

    </div>

    <div class="col-md-6 mb-3">
        <label>Fee Type <span class="text-danger">*</span></label>
        <input type="text" name="fee_type" value="{{ old('fee_type', $feeStructure->fee_type ?? '') }}"
            class="form-control" placeholder="e.g. Tuition Fee" required>
    </div>

    <div class="col-md-6 mb-3">
        <label>Frequency <span class="text-danger">*</span></label>
        <select name="frequency" class="form-control" required>
            <option value="">-- Select --</option>
            <option value="monthly"
                {{ old('frequency', $feeStructure->frequency ?? '') == 'monthly' ? 'selected' : '' }}>
                Monthly</option>
            <option value="one_time"
                {{ old('frequency', $feeStructure->frequency ?? '') == 'one_time' ? 'selected' : '' }}>
                One Time</option>
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label>Amount (₹) <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="amount" value="{{ old('amount', $feeStructure->amount ?? '') }}"
            class="form-control" placeholder="Enter fee amount" required>
    </div>
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label>Session <span class="text-danger">*</span></label>
            <select name="session" class="form-control" required>
                <option value="">Select Session</option>
                @foreach ($sessions as $ses)
                    <option value="{{ $ses->name }}"
                        {{ old('session', $feeStructure->session ?? $activeSession) == $ses->name ? 'selected' : '' }}>
                        {{ $ses->name }} {{ $ses->is_active ? '(Active)' : '' }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

</div>
