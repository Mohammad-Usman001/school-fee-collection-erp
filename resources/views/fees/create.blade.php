@extends('layouts.app')

@section('title', 'Collect Fee')
@section('page_title', 'Collect Fee (Professional System)')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('fees.index') }}">Fee Payments</a></li>
    <li class="breadcrumb-item active">Collect</li>
@endsection

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                <i class="fas fa-hand-holding-usd mr-1"></i> Collect Fee
            </h3>

            <a href="{{ route('fees.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <form action="{{ route('fees.store') }}" method="POST" id="feeForm">
            @csrf

            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <h6 class="mb-1">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Payment Error
                        </h6>
                        <ul class="mb-0 pl-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif

                {{-- ================= TOP INPUTS ================= --}}
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Session <span class="text-danger">*</span></label>
                        <select name="session" id="session" class="form-control" required>
                            <option value="">Select Session</option>
                            @foreach ($sessions as $ses)
                                <option value="{{ $ses->name }}" {{ $ses->name == $activeSession ? 'selected' : '' }}>
                                    {{ $ses->name }} {{ $ses->is_active ? '(Active)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Session April–March</small>
                    </div>

                    {{-- STUDENT SEARCH --}}
                    <div class="col-md-5 mb-3 form-group position-relative">
                        <label>Search Student (Name / ID / Phone) <span class="text-danger">*</span></label>
                        <input type="text" id="studentSearch" class="form-control"
                            placeholder="Type at least 2 letters..." autocomplete="off">

                        <div class="list-group mt-1" id="studentResults"
                            style="max-height:230px; overflow:auto; display:none; position:absolute; width:100%; z-index:9999;">
                        </div>

                        <input type="hidden" name="student_id" id="student_id">
                    </div>

                    {{-- MONTH --}}
                    <div class="col-md-4 mb-3">
                        <label>Select Months <span class="text-danger">*</span></label>

                        <div id="sessionMonthsBox" class="border rounded p-2" style="max-height:180px; overflow:auto;">
                            <span class="text-muted">Select session & student first</span>
                        </div>

                        <small class="text-muted">
                            You can select multiple months (April–March)
                        </small>
                    </div>

                </div>

                {{-- STUDENT INFO --}}
                <div class="alert alert-info d-none" id="studentInfo"></div>

                {{-- ================= CURRENT MONTH SUMMARY ================= --}}
                <div class="card card-outline card-info mt-2 d-none" id="monthSummaryCard">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-calendar-alt mr-1"></i> Selected Month Invoice Summary
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap" style="gap:10px;">
                            <span class="badge badge-primary p-2" style="font-size: 13px;">
                                Month Total: ₹<span id="monthTotal">0</span>
                            </span>
                            <span class="badge badge-success p-2" style="font-size: 13px;">
                                Month Paid: ₹<span id="monthPaid">0</span>
                            </span>
                            <span class="badge badge-warning p-2" style="font-size: 13px;">
                                Month Due: ₹<span id="monthDue">0</span>
                            </span>
                            <span class="badge badge-secondary p-2" style="font-size: 13px;">
                                Status: <span id="monthStatus">NEW</span>
                            </span>
                        </div>
                        <small class="text-muted d-block mt-2">
                            Invoice exists => locked. New month => generated from fee structure.
                        </small>
                    </div>
                </div>

                {{-- ================= INSTALLMENT HISTORY ================= --}}
                <div class="card card-outline card-secondary mt-2 d-none" id="monthHistoryCard">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-history mr-1"></i> Installments (Selected Month)
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Receipt</th>
                                        <th>Mode</th>
                                        <th class="text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="monthPaymentsBody">
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">
                                            Select month to see history...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="text-right mt-2">
                            <span class="badge badge-success p-2" style="font-size: 13px;">
                                Paid in this month: ₹<span id="paidThisMonth">0</span>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- ================= PAYMENT INPUTS ================= --}}
                <div class="row mt-3">
                    <div class="col-md-4 mb-3">
                        <label>Paid Date <span class="text-danger">*</span></label>
                        <input type="date" name="paid_date" class="form-control" value="{{ now()->format('Y-m-d') }}"
                            required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Payment Mode <span class="text-danger">*</span></label>
                        <select name="payment_mode" class="form-control" required>
                            <option value="cash">Cash</option>
                            <option value="upi">UPI</option>
                            <option value="bank">Bank</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Pay Now (Installment) ₹ <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="paid_amount" id="paid_amount" class="form-control"
                            value="0" required>
                        <small class="text-muted">
                            Amount will auto distribute month-wise (FIFO) in backend
                        </small>
                        @error('paid_amount')
                            <small class="text-danger font-weight-bold">
                                <i class="fas fa-info-circle"></i> {{ $message }}
                            </small>
                        @enderror

                    </div>
                </div>

                <hr>

                {{-- ================= MULTI MONTH SELECT (NEW) ================= --}}
                <div class="card card-outline card-warning mb-3 d-none" id="multiMonthCard">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-layer-group mr-1"></i> Multi-Month Payment (Optional)
                        </h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-2">
                            If parent pays 2-3 months together, select multiple months invoices to distribute amount.
                            Default: selected month invoice always included.
                        </p>

                        <div id="invoiceListBox" class="d-flex flex-wrap" style="gap:10px;"></div>

                        {{-- hidden invoice ids --}}
                        <div id="invoiceIdsArea"></div>
                    </div>
                </div>

                {{-- ================= FEE ITEMS (Invoice Heads) ================= --}}
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0"><i class="fas fa-list mr-1"></i> Invoice Fee Heads</h5>
                    <span class="text-muted" style="font-weight:600;">
                        Monthly + unpaid one-time heads
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th width="60" class="text-center">Lock</th>
                                <th>Fee Head</th>
                                <th width="200" class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody id="feeItemsBody">
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">
                                    Select student + month to load invoice...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- ================= TOTALS ================= --}}
                <div class="row mt-3">
                    <div class="col-md-4 mb-3">
                        <label>Invoice Total (₹)</label>
                        <input type="text" id="total_amount" class="form-control" readonly value="0.00">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Due After This Payment (₹)</label>
                        <input type="text" id="due_amount" class="form-control" readonly value="0.00">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Status</label>
                        <input type="text" id="pay_status" class="form-control" readonly value="Pending">
                    </div>
                </div>

            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Payment & Generate Receipt
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        /* ================= URLS ================= */
        const studentSearchUrl = "{{ route('fees.studentSearch') }}";
        const loadInvoiceUrl = "{{ route('fees.loadInvoice') }}";
        const monthPaymentsUrl = "{{ route('fees.monthPayments') }}";

        /* ================= ELEMENTS ================= */
        const sessionEl = document.getElementById('session');
        const studentSearch = document.getElementById('studentSearch');
        const studentResults = document.getElementById('studentResults');
        const studentInfo = document.getElementById('studentInfo');
        const studentIdInput = document.getElementById('student_id');

        const monthsBox = document.getElementById('sessionMonthsBox');

        const feeItemsBody = document.getElementById('feeItemsBody');
        const totalAmount = document.getElementById('total_amount');
        const paidAmount = document.getElementById('paid_amount');
        const dueAmount = document.getElementById('due_amount');
        const payStatus = document.getElementById('pay_status');

        const monthSummaryCard = document.getElementById('monthSummaryCard');
        const monthTotalEl = document.getElementById('monthTotal');
        const monthDueEl = document.getElementById('monthDue');

        const invoiceIdsArea = document.getElementById('invoiceIdsArea');

        /* ================= HELPERS ================= */
        function money(n) {
            return parseFloat(n || 0).toFixed(2);
        }

        /* ================= STATE ================= */
        let selectedStudent = null;
        let currentMonthDue = 0;

        /* ================= RESET UI ================= */
        function resetUI() {
            feeItemsBody.innerHTML = `
        <tr>
            <td colspan="3" class="text-center text-muted py-3">
                Select student + months to load invoice...
            </td>
        </tr>`;

            totalAmount.value = "0.00";
            dueAmount.value = "0.00";
            payStatus.value = "Pending";
            paidAmount.value = "0";

            monthSummaryCard.classList.add('d-none');
            invoiceIdsArea.innerHTML = "";
            currentMonthDue = 0;
        }

        /* ================= STUDENT SEARCH ================= */
        let typingTimer = null;

        studentSearch.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
            const q = this.value.trim();
            if (q.length < 2) {
                studentResults.style.display = "none";
                return;
            }

            typingTimer = setTimeout(() => {
                fetch(studentSearchUrl + "?q=" + encodeURIComponent(q) + "&session=" + sessionEl.value)
                    .then(res => res.json())
                    .then(data => {
                        studentResults.innerHTML = "";
                        if (data.length === 0) {
                            studentResults.innerHTML =
                                `<div class="list-group-item text-muted">No student found</div>`;
                            studentResults.style.display = "block";
                            return;
                        }

                        data.forEach(st => {
                            const btn = document.createElement("button");
                            btn.type = "button";
                            btn.className = "list-group-item list-group-item-action";
                            btn.innerHTML = `
                    <strong>${st.name}</strong> - ${st.unique_id}
                    <br><small class="text-muted">Class: ${st.class} ${st.section ?? ''}</small>
                `;
                            btn.onclick = () => selectStudent(st);
                            studentResults.appendChild(btn);
                        });

                        studentResults.style.display = "block";
                    });
            }, 300);
        });

        document.addEventListener('click', e => {
            if (!studentResults.contains(e.target) && e.target !== studentSearch) {
                studentResults.style.display = "none";
            }
        });

        /* ================= SELECT STUDENT ================= */
        function selectStudent(st) {
            selectedStudent = st;
            studentSearch.value = `${st.name} (${st.unique_id})`;
            studentIdInput.value = st.id;
            studentResults.style.display = "none";

            studentInfo.classList.remove('d-none');
            studentInfo.innerHTML = `
        <strong>Selected Student:</strong> ${st.name} | ${st.unique_id}
        <br><small>Class: ${st.class} ${st.section ?? ''}</small>
    `;

            resetUI();
            renderSessionMonths();
        }

        /* ================= SESSION BASED MONTHS ================= */
        function generateSessionMonths(session) {
            const parts = session.split('-');
            const startYear = parseInt(parts[0]);
            const endYear = parseInt(parts[0].slice(0, 2) + parts[1]);

            return [
                `${startYear}-04`, `${startYear}-05`, `${startYear}-06`,
                `${startYear}-07`, `${startYear}-08`, `${startYear}-09`,
                `${startYear}-10`, `${startYear}-11`, `${startYear}-12`,
                `${endYear}-01`, `${endYear}-02`, `${endYear}-03`
            ];
        }

        function renderSessionMonths() {
            if (!sessionEl.value || !selectedStudent) {
                monthsBox.innerHTML = `<span class="text-muted">Select session & student</span>`;
                return;
            }

            const months = generateSessionMonths(sessionEl.value);
            monthsBox.innerHTML = "";

            months.forEach(m => {
                const label = new Date(m + "-01").toLocaleString('default', {
                    month: 'long',
                    year: 'numeric'
                });

                monthsBox.innerHTML += `
            <label class="d-block">
                <input type="checkbox" class="month-check" value="${m}">
                ${label}
            </label>
        `;
            });

            document.querySelectorAll('.month-check').forEach(chk => {
                chk.addEventListener('change', loadSelectedMonthsInvoice);
            });
        }

        /* ================= LOAD MULTI MONTH INVOICE ================= */
        function loadSelectedMonthsInvoice() {
            const months = Array.from(document.querySelectorAll('.month-check:checked'))
                .map(el => el.value);

            if (months.length === 0) {
                resetUI();
                return;
            }

            fetch(loadInvoiceUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        student_id: selectedStudent.id,
                        session: sessionEl.value,
                        months: months
                    })
                })
                .then(res => res.json())
                .then(data => renderInvoices(data));
        }

        /* ================= RENDER INVOICES ================= */
        function renderInvoices(data) {
            feeItemsBody.innerHTML = "";
            invoiceIdsArea.innerHTML = "";

            let total = 0;
            let due = 0;

            data.invoices.forEach(inv => {

                invoiceIdsArea.innerHTML +=
                    `<input type="hidden" name="invoice_ids[]" value="${inv.id}">`;

                inv.items.forEach(it => {
                    feeItemsBody.innerHTML += `
                <tr>
                    <td class="text-center">
                        <span class="badge badge-success">LOCK</span>
                    </td>
                    <td>
                        <strong>${it.head.name}</strong>
                        <br><small class="text-muted">${inv.month}</small>
                    </td>
                    <td class="text-right">₹${money(it.amount)}</td>
                </tr>
            `;
                });

                total += parseFloat(inv.total_amount);
                due += parseFloat(inv.due_amount);
            });

            totalAmount.value = money(total);
            currentMonthDue = due;
            calculateDue();

            // summary
            monthSummaryCard.classList.remove('d-none');
            monthTotalEl.innerText = money(data.summary.total);
            document.getElementById('monthPaid').innerText = money(data.summary.paid);
            monthDueEl.innerText = money(data.summary.due);
            document.getElementById('monthStatus').innerText = data.summary.status;

            // update state
            currentMonthDue = parseFloat(data.summary.due);
            if (currentMonthDue <= 0) {
                paidAmount.value = "0";
                paidAmount.setAttribute("readonly", true);
                paidAmount.classList.add("bg-light");
            } else {
                paidAmount.removeAttribute("readonly");
                paidAmount.classList.remove("bg-light");
            }

        }

        /* ================= DUE CALC ================= */
        paidAmount.addEventListener('keyup', calculateDue);
        paidAmount.addEventListener('change', calculateDue);

        // function calculateDue() {
        //     let paid = parseFloat(paidAmount.value || 0);
        //     let due = currentMonthDue - paid;
        //     if (due < 0) due = 0;

        //     dueAmount.value = money(due);

        //     if (currentMonthDue === 0) payStatus.value = "Pending";
        //     else if (due === 0) payStatus.value = "Fully Paid";
        //     else payStatus.value = "Partial Paid";
        // }
        function calculateDue() {
            let paid = parseFloat(paidAmount.value || 0);

            if (paid > currentMonthDue) {
                paid = currentMonthDue;
                paidAmount.value = money(currentMonthDue);

                alert("You cannot pay more than due amount!");
            }

            let due = currentMonthDue - paid;
            dueAmount.value = money(due);

            if (currentMonthDue === 0) payStatus.value = "Paid";
            else if (due === 0) payStatus.value = "Fully Paid";
            else payStatus.value = "Partial Paid";
        }

        /* ================= SESSION CHANGE ================= */
        sessionEl.addEventListener('change', function() {
            selectedStudent = null;
            studentSearch.value = "";
            studentIdInput.value = "";
            studentInfo.classList.add('d-none');
            monthsBox.innerHTML = `<span class="text-muted">Select session & student</span>`;
            resetUI();
        });

        /* ================= SUBMIT VALIDATION ================= */
        document.getElementById('feeForm').addEventListener('submit', function(e) {

            if (!studentIdInput.value) {
                e.preventDefault();
                alert("Please select student!");
                return;
            }

            const months = document.querySelectorAll('.month-check:checked');
            if (months.length === 0) {
                e.preventDefault();
                alert("Please select at least one month!");
                return;
            }

            if (parseFloat(paidAmount.value || 0) <= 0) {
                e.preventDefault();
                alert("Pay Now amount must be greater than 0!");
                return;
            }
        });
    </script>
@endpush
