<div class="row">
    <div class="col-md-6 mb-3">
        <label>Name <span class="text-danger">*</span></label>
        <input type="text" name="name" value="{{ old('name', $student->name ?? '') }}" class="form-control"
            placeholder="Student name" required>
    </div>

    <div class="col-md-6 mb-3">
        <label>Father Name</label>
        <input type="text" name="father_name" value="{{ old('father_name', $student->father_name ?? '') }}"
            class="form-control" placeholder="Father name">
    </div>

    @isset($student)
        <div class="col-md-6 mb-3">
            <label>Unique ID <span class="text-danger">*</span></label>
            <input type="text" name="unique_id" value="{{ old('unique_id', $student->unique_id ?? '') }}"
                class="form-control" required>
            <small class="text-muted">This ID must be unique.</small>
        </div>
    @endisset

    <div class="col-md-4 mb-3">
        <label>Class <span class="text-danger">*</span></label>
        <select name="class" class="form-control" required>
            <option value="">Select Class</option>
            @foreach ($classes as $c)
                <option value="{{ $c->name }}"
                    {{ old('class', $student->class ?? '') == $c->name ? 'selected' : '' }}>
                    {{ $c->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label>Section</label>
        <select name="section" class="form-control">
            <option value="">Select Section</option>
            @foreach ($sections as $s)
                <option value="{{ $s->name }}"
                    {{ old('section', $student->section ?? '') == $s->name ? 'selected' : '' }}>
                    {{ $s->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label>Session <span class="text-danger">*</span></label>
        <select name="session" class="form-control" required>
            <option value="">Select Session</option>

            @foreach ($sessions as $ses)
                <option value="{{ $ses->name }}"
                    {{ old('session', $student->session ?? $activeSession) == $ses->name ? 'selected' : '' }}>
                    {{ $ses->name }} {{ $ses->is_active ? '(Active)' : '' }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label>Phone</label>
        <input type="text" name="phone" value="{{ old('phone', $student->phone ?? '') }}" class="form-control"
            placeholder="Phone number">
    </div>

    <div class="col-md-12 mb-3">
        <label>Address</label>
        <textarea name="address" class="form-control" rows="3" placeholder="Full address">{{ old('address', $student->address ?? '') }}</textarea>
    </div>
</div>
