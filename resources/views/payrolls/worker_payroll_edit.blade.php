@extends('layouts.app')

@section('content')
<style>
/* Body Gradient */
body {
    background: linear-gradient(135deg, #e0e7ff, #f3e8ff, #ffe4f0);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Card */
.card {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(10px);
    border-radius: 25px;
    max-width: 700px;
    margin: 50px auto;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    border: 1px solid #ddd;
    transition: transform 0.3s ease;
}

.card:hover {
    transform: scale(1.02);
}

/* Header */
.card-header {
    background: linear-gradient(90deg, #4f46e5, #8b5cf6);
    padding: 25px;
    color: white;
}

.card-header h2 {
    margin: 0;
    font-size: 28px;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
}

.card-header p {
    margin: 5px 0 0;
    font-size: 14px;
}

/* Batch Info */
.batch-info {
    background: #f3e8ff;
    padding: 15px 25px;
    border-bottom: 1px solid #ddd;
    color: #5b21b6;
}

.batch-info h3 {
    margin: 0 0 5px;
    font-weight: 600;
}

.batch-info p {
    margin: 0;
    font-size: 13px;
}

/* Form */
.card form {
    padding: 25px;
}

.card form .form-group {
    margin-bottom: 15px;
}

.card form label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #4b5563;
}

.card form input[type="text"],
.card form input[type="number"],
.card form input[type="date"],
.card form textarea,
.card form select {
    width: 100%;
    padding: 12px;
    border-radius: 12px;
    border: 1px solid #ccc;
    outline: none;
    transition: all 0.3s ease;
}

.card form input:focus,
.card form textarea:focus,
.card form select:focus {
    border-color: #8b5cf6;
    box-shadow: 0 0 5px rgba(139, 92, 246, 0.5);
}

/* Total Payment Highlight */
.total-payment {
    background: #dcfce7;
    border: 1px solid #86efac;
    padding: 15px;
    border-radius: 12px;
    font-weight: bold;
    color: #16a34a;
}

/* Submit Button */
.card form button {
    background: linear-gradient(90deg, #8b5cf6, #4f46e5);
    color: white;
    font-weight: 600;
    padding: 12px 25px;
    border: none;
    border-radius: 15px;
    cursor: pointer;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
}

.card form button:hover {
    transform: translateY(-2px) scale(1.02);
    box-shadow: 0 8px 20px rgba(0,0,0,0.25);
}
</style>

<div class="card">
    <!-- Header -->
    <div class="card-header">
        <h2>Edit Worker Payroll</h2>
        <p>Updating payroll for <strong>{{ $assignment->employee->name }}</strong></p>
    </div>

    <!-- Batch Info -->
    <div class="batch-info">
        <h3>Batch: {{ $batch->batch_no }} | Product: {{ $batch->product->name }}</h3>
        <p>PO: {{ $batch->po_no ?? 'N/A' }} | Priority: <strong>{{ ucfirst($batch->priority) }}</strong></p>
    </div>

    <!-- Form -->
    <form action="{{ route('payrolls.worker_payroll_update', $assignment->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="batch_id" value="{{ $batch->id }}">
        <input type="hidden" name="employee_id" value="{{ $assignment->employee_id }}">

        <div class="form-group">
            <label>Labor Type</label>
            <input type="text" value="{{ $assignment->employee->labor_type }}" readonly>
        </div>

        <div class="form-group">
            <label>Salary per Day (₹)</label>
            <input type="number" name="salary_per_day" id="salary" step="0.01"
                   value="{{ old('salary_per_day', $assignment->salary_per_day) }}"
                   oninput="calculateTotalPayment()" required>
        </div>

        <div class="form-group">
            <label>Days Worked</label>
            <input type="number" name="days_worked" id="days_worked"
                   value="{{ old('days_worked', $assignment->days_worked) }}"
                   oninput="calculateTotalPayment()" required>
        </div>

        <div class="form-group">
            <label>Units Completed</label>
            <input type="number" name="completed_units" value="{{ old('completed_units', $assignment->completed_units ?? 0) }}">
        </div>

        <div class="form-group">
            <label>Labor Status</label>
            <select name="labor_status" id="labor_status">
                <option value="pending" {{ $assignment->labor_status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ $assignment->labor_status === 'paid' ? 'selected' : '' }}>Paid</option>
            </select>
        </div>

        <div class="form-group">
            <label>Start Date</label>
            <input type="date" name="start_date" value="{{ old('start_date', optional($assignment->start_date)?->format('Y-m-d')) }}" required>
        </div>

        <div class="form-group">
            <label>End Date</label>
            <input type="date" name="end_date" value="{{ old('end_date', optional($assignment->end_date)?->format('Y-m-d')) }}" required>
        </div>

        <div class="form-group total-payment">
            <label>Total Payment (₹)</label>
            <input type="number" id="total_payment" name="total_payment" readonly
                   value="{{ number_format($assignment->salary_per_day * $assignment->days_worked, 2) }}">
        </div>

        <div class="form-group">
            <label>Payment Date</label>
            <input type="date" name="payment_date"
                   value="{{ old('payment_date', optional($assignment->payment_date)?->format('Y-m-d') ?? date('Y-m-d')) }}" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3">{{ old('description', $assignment->description) }}</textarea>
        </div>

        <div class="form-group" style="text-align:right;">
            <button type="submit">Update Payroll</button>
        </div>
    </form>
</div>

<script>
function calculateTotalPayment() {
    const salary = parseFloat(document.getElementById('salary').value) || 0;
    const days = parseFloat(document.getElementById('days_worked').value) || 0;
    document.getElementById('total_payment').value = (salary * days).toFixed(2);
}
window.addEventListener('DOMContentLoaded', calculateTotalPayment);
</script>
@endsection
