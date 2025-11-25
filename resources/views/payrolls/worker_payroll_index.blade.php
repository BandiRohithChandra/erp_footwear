@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6 bg-gray-50 min-h-screen font-sans">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Worker Payroll Management</h2>

 <!-- Filters Section -->
<div class="flex flex-wrap items-center justify-between gap-4 mb-8 bg-white p-4 rounded-xl shadow-sm border border-gray-200">

    <!-- Left: Status Tabs -->
    <div class="flex flex-wrap gap-3">
        @php
            $statusButtons = [
                'all' => ['label' => 'All', 'color' => 'bg-gray-100 text-gray-800 hover:bg-gray-200'],
                'paid' => ['label' => 'Paid', 'color' => 'bg-green-100 text-green-800 hover:bg-green-200'],
                'partially_paid' => ['label' => 'Partially Paid', 'color' => 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200'],
                'pending' => ['label' => 'Pending / Due', 'color' => 'bg-red-100 text-red-800 hover:bg-red-200'],
            ];
        @endphp

        @foreach ($statusButtons as $key => $btn)
            <button 
                class="status-tab px-4 py-2 rounded-lg font-semibold border transition-all duration-200
                {{ $statusFilter == $key 
                    ? 'bg-blue-600 text-white border-blue-600 shadow-sm' 
                    : $btn['color'] . ' border border-gray-300' }}"
                data-status="{{ $key }}">
                {{ $btn['label'] }}
            </button>
        @endforeach
    </div>

    <!-- Right: Worker Status + Search -->
    <div class="flex flex-wrap items-center gap-3">

        <!-- Worker Status Dropdown -->
        <div class="relative">
            <select id="statusFilterDropdown"
                class="appearance-none border border-gray-300 rounded-lg px-4 py-2 pr-8 text-gray-700 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 bg-white cursor-pointer">
                <option value="all">All Workers</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="onhold">On-Hold</option>
            </select>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 absolute right-2 top-2.5 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>

        <!-- Search Input -->
        <div class="relative">
            <input 
                type="text" 
                id="searchInput" 
                placeholder="Search by worker name..." 
                class="border border-gray-300 rounded-lg px-4 py-2 pl-10 w-64 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition"
            >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 absolute left-2.5 top-2.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1016.65 16.65z" />
            </svg>
        </div>
    </div>
</div>


    <!-- Payroll Table -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Employees</h3>

        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse border border-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr class="text-gray-700">
                        <th class="py-2 px-3 border-b text-left">S.No</th>
                        <th class="py-2 px-3 border-b text-left">Worker Name</th>
                         <th class="py-2 px-3 border-b text-left">Employee Type</th>
                        <th class="py-2 px-3 border-b text-right">Salary (₹)</th>
                        <th class="py-2 px-3 border-b text-right">Paid (₹)</th>
                        <th class="py-2 px-3 border-b text-right">Advance (₹)</th>
                        <th class="py-2 px-3 border-b text-right">Deduction (₹)</th>
                        <th class="py-2 px-3 border-b text-right">Remaining Due (₹)</th>
                        <th class="py-2 px-3 border-b text-right">Pay Now (₹)</th>
                        <th class="py-2 px-3 border-b text-center">Payment Status</th>
                        <th class="py-2 px-3 border-b text-center">Work Status</th>
                        <th class="py-2 px-3 border-b text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $index => $employee)
                    <tr class="hover:bg-gray-50 transition-colors"
                        data-employee-id="{{ $employee['employee_id'] ?? 0 }}"
                        data-employee-url="{{ route('payrolls.worker_show', $employee['employee_id'] ?? 0) }}"
                        data-name="{{ strtolower($employee['employee_name'] ?? '') }}"
                        data-work-status="{{ strtolower($employee['work_status'] ?? 'active') }}">

                        <td class="py-2 px-3 border-b">{{ $index + 1 }}</td>
                        <td class="py-2 px-3 border-b font-medium">{{ $employee['employee_name'] ?? '-' }}</td>
                        <td class="py-2 px-3 border-b font-medium">{{ $employee['type'] ?? '-' }}</td>
                        <td class="py-2 px-3 border-b text-right salaryCell">₹{{ number_format($employee['salary'], 2) }}</td>
                        <td class="py-2 px-3 border-b text-right text-green-600 paidCell">₹{{ number_format($employee['paid'], 2) }}</td>
                        <td class="py-2 px-3 border-b text-right text-yellow-600 advanceCell">₹{{ number_format($employee['advance'], 2) }}</td>
                        <td class="py-2 px-3 border-b text-right">
                            <input type="number" class="deductAmount border border-gray-300 rounded-lg p-1 text-center w-20 focus:ring-1 focus:ring-blue-400" step="0.01" min="0" placeholder="0">
                        </td>
                        <td class="py-2 px-3 border-b text-right text-red-600 dueCell">₹{{ number_format($employee['due'], 2) }}</td>
                        <td class="py-2 px-3 border-b text-right">
                            <input type="number" class="totalPay border border-gray-300 rounded-lg p-1 text-center w-24 focus:ring-1 focus:ring-blue-400" step="0.01" min="0" placeholder="0" value="{{ number_format($employee['due'], 2) }}">
                        </td>
                       @php
$status = strtolower($employee['payment_status'] ?? 'pending');
$statusColor = match($status) {
    'paid' => 'text-green-600 bg-green-50 border border-green-200',
    'partially_paid' => 'text-yellow-600 bg-yellow-50 border border-yellow-200',
    'pending' => 'text-red-600 bg-red-50 border border-red-200',
    default => 'text-gray-600 bg-gray-50 border border-gray-200',
};
@endphp

<td class="py-2 px-3 border-b text-center font-semibold paymentStatus">
    <span class="px-2 py-1 rounded-full text-sm {{ $statusColor }}">
        {{ ucfirst(str_replace('_', ' ', $employee['payment_status'] ?? 'Pending')) }}
    </span>
</td>


                        <td class="py-2 px-3 border-b text-center">
                            <select class="workStatus border border-gray-300 rounded-lg p-1 w-28 text-center">
                                <option value="active" {{ strtolower($employee['work_status'] ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ strtolower($employee['work_status'] ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="onhold" {{ strtolower($employee['work_status'] ?? '') == 'on-hold' ? 'selected' : '' }}>On-Hold</option>
                            </select>
                        </td>
                        <td class="py-2 px-3 border-b text-center">
                            <form method="POST" action="{{ route('payrolls.worker_pay_now', $employee['employee_id'] ?? 0) }}">
                                @csrf
                                <input type="hidden" class="deductHidden" name="deduct_amount" value="0">
                                <input type="hidden" class="totalPayHidden" name="total_pay" value="{{ number_format($employee['due'], 2) }}">
                                <button type="submit" class="payNowBtn bg-blue-600 text-white px-3 py-1 rounded-lg hover:bg-blue-700 transition-colors">Pay Now</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="py-4 text-gray-500 text-center">No employees found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const rows = document.querySelectorAll('tbody tr');
    const searchInput = document.getElementById('searchInput');
    const statusButtons = document.querySelectorAll('.status-tab');
    const statusFilterDropdown = document.getElementById('statusFilterDropdown');
    let currentStatus = 'all';
    let currentWorkStatus = 'all';

    const filterRows = () => {
        const search = searchInput.value.toLowerCase();

        rows.forEach(row => {
            const name = row.dataset.name;
            const salary = parseFloat(row.querySelector('.salaryCell').textContent.replace(/[₹,]/g, '')) || 0;
            const paid = parseFloat(row.querySelector('.paidCell').textContent.replace(/[₹,]/g, '')) || 0;
            const advance = parseFloat(row.querySelector('.advanceCell').textContent.replace(/[₹,]/g, '')) || 0;
            const due = Math.max(salary - paid - advance, 0);
            const workStatus = row.querySelector('.workStatus').value;

            let statusMatch = currentStatus === 'all' || (currentStatus === 'paid' ? due <= 0 : due > 0);
            let workStatusMatch = currentWorkStatus === 'all' || workStatus === currentWorkStatus;

            row.style.display = (name.includes(search) && statusMatch && workStatusMatch) ? '' : 'none';
        });
    };

    searchInput.addEventListener('input', filterRows);
    statusButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            currentStatus = btn.dataset.status;
            statusButtons.forEach(b => b.classList.remove('bg-blue-600','text-white','border-blue-600'));
            btn.classList.add('bg-blue-600','text-white','border-blue-600');
            filterRows();
        });
    });

    statusFilterDropdown.addEventListener('change', () => {
        currentWorkStatus = statusFilterDropdown.value;
        filterRows();
    });

    rows.forEach(row => {
        const salaryCell = row.querySelector('.salaryCell');
        const paidCell = row.querySelector('.paidCell');
        const advanceCell = row.querySelector('.advanceCell');
        const dueCell = row.querySelector('.dueCell');
        const deductInput = row.querySelector('.deductAmount');
        const payInput = row.querySelector('.totalPay');
        const deductHidden = row.querySelector('.deductHidden');
        const payHidden = row.querySelector('.totalPayHidden');
        const statusCell = row.querySelector('.paymentStatus');
        const workStatusSelect = row.querySelector('.workStatus');
        const payBtn = row.querySelector('.payNowBtn');

        let salary = parseFloat(salaryCell.textContent.replace(/[₹,]/g, '')) || 0;
        let paid = parseFloat(paidCell.textContent.replace(/[₹,]/g, '')) || 0;
        let advance = parseFloat(advanceCell.textContent.replace(/[₹,]/g, '')) || 0;

        const updateRow = () => {
            let deduct = parseFloat(deductInput.value) || 0;
            deduct = Math.min(deduct, advance);

            let remainingAdvance = Math.max(advance - deduct, 0);
            let remainingDue = Math.max(salary - paid - deduct, 0);

            advanceCell.textContent = `₹${remainingAdvance.toFixed(2)}`;
            dueCell.textContent = `₹${remainingDue.toFixed(2)}`;

            // if (remainingDue <= 0 && remainingAdvance <= 0) {
            //     statusCell.textContent = 'Paid';
            //     statusCell.classList.replace('text-blue-600','text-green-600');
            // } else {
            //     statusCell.textContent = 'Pending';
            //     statusCell.classList.replace('text-green-600','text-blue-600');
            // }

            deductHidden.value = deduct.toFixed(2);
            if (!payInput.dataset.userEdited) payInput.value = remainingDue.toFixed(2);
            payHidden.value = parseFloat(payInput.value || 0).toFixed(2);

            // Disable Pay Now for inactive/on-hold
            const workStatus = workStatusSelect.value;
            if(['inactive','onhold'].includes(workStatus)) {
                payBtn.disabled = true;
                payBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                payBtn.disabled = false;
                payBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }

            filterRows();
        };

        deductInput.addEventListener('input', updateRow);
        payInput.addEventListener('input', () => { payInput.dataset.userEdited = true; updateRow(); });

        // Update row whenever work status changes
        workStatusSelect.addEventListener('change', updateRow);

        row.querySelector('.payNowBtn').addEventListener('click', () => updateRow());

        row.addEventListener('click', e => {
            if (['INPUT','BUTTON','SELECT'].includes(e.target.tagName)) return;
            window.location.href = row.dataset.employeeUrl;
        });

        updateRow(); // Initialize
    });

    filterRows();
});
</script>
@endsection
