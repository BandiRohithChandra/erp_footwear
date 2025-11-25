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
    background: rgba(255, 255, 255, 0.9);
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
        <h2>Create Worker Payroll</h2>
        <p>Fill in the details to assign payroll to a worker.</p>
    </div>

    <!-- Form -->
    <form action="{{ route('payrolls.worker_payroll_store') }}" method="POST">
        @csrf

        <!-- Employee Selection -->
        <div class="form-group">
            <label for="employee_id">Select Worker</label>
            <select name="employee_id" id="employee_id" required onchange="updateEmployeeDetails()">
                <option value="">-- Select Worker --</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}"
                            data-labor-type="{{ $employee->labor_type }}"
                            data-salary="{{ $employee->labor_amount }}"
                            {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                        {{ $employee->name }} ({{ $employee->employee_id }}) - {{ ucfirst($employee->role) }}
                    </option>
                @endforeach
            </select>
            @error('employee_id')
                <span style="color:red;font-size:13px">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group" style="display:flex; gap:15px;">
            <div style="flex:1">
                <label for="labor_type">Labor Type</label>
                <input type="text" name="labor_type" id="labor_type" readonly value="{{ old('labor_type') }}">
            </div>
            <div style="flex:1">
                <label for="salary">Salary/Day (₹)</label>
                <input type="number" name="salary" id="salary" readonly value="{{ old('salary') }}">
            </div>
        </div>

        <!-- Batch & Process -->
        <div class="form-group" style="display:flex; gap:15px;">
            <div style="flex:1">
                <label for="batch_id">Batch (Optional)</label>
                <select name="batch_id" id="batch_id">
                    <option value="">-- Select Batch --</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}" {{ old('batch_id') == $batch->id ? 'selected' : '' }}>
                            {{ $batch->batch_no }} (Order: {{ $batch->order_no }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="flex:1">
                <label for="process_id">Process (Optional)</label>
                <select name="process_id" id="process_id">
                    <option value="">-- Select Process --</option>
                    @foreach($processes as $process)
                        <option value="{{ $process->id }}" {{ old('process_id') == $process->id ? 'selected' : '' }}>
                            {{ $process->name }} ({{ $process->stage }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Days Worked & Units -->
        <div class="form-group" style="display:flex; gap:15px;">
            <div style="flex:1">
                <label for="days_worked">Days Worked</label>
                <input type="number" min="0" name="days_worked" id="days_worked" value="{{ old('days_worked') }}" oninput="calculateTotalPayment()">
            </div>
            <div style="flex:1">
                <label for="completed_units">Units Completed</label>
                <input type="number" min="0" name="completed_units" id="completed_units" value="{{ old('completed_units') }}">
            </div>
            <div style="flex:1">
                <label for="total_payment">Total Payment (₹)</label>
                <input type="number" name="total_payment" id="total_payment" readonly value="{{ old('total_payment') }}" class="total-payment">
            </div>
        </div>

        <!-- Payment Date -->
        <div class="form-group">
            <label for="payment_date">Payment Date</label>
            <input type="date" name="payment_date" id="payment_date" required value="{{ old('payment_date', date('Y-m-d')) }}">
        </div>

        <!-- Description -->
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="2" placeholder="Optional description">{{ old('description') }}</textarea>
        </div>

        <div class="form-group" style="text-align:right;">
            <button type="submit">Create Payroll</button>
        </div>
    </form>
</div>

<script>
function updateEmployeeDetails() {
    const select = document.getElementById('employee_id');
    const laborTypeInput = document.getElementById('labor_type');
    const salaryInput = document.getElementById('salary');

    const selected = select.options[select.selectedIndex];
    laborTypeInput.value = selected.dataset.laborType || '';
    salaryInput.value = selected.dataset.salary || '';

    calculateTotalPayment();
}

function calculateTotalPayment() {
    const salary = parseFloat(document.getElementById('salary').value) || 0;
    const days = parseFloat(document.getElementById('days_worked').value) || 0;
    document.getElementById('total_payment').value = (salary * days).toFixed(2);
}

window.addEventListener('DOMContentLoaded', updateEmployeeDetails);
</script>
@endsection
