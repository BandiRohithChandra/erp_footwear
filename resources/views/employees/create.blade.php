@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-lg shadow max-w-5xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">{{ __('Add New Employee') }}</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-6">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 text-red-700 p-4 rounded mb-6">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- ===== 1. Basic Info (2 columns) ===== -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="block text-gray-700 font-medium mb-2">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Role <span class="text-red-500">*</span></label>
                <select name="role" id="role" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('role') border-red-500 @enderror">
                    <option value="Employee" {{ old('role') == 'Employee' ? 'selected' : '' }}>Employee</option>
                    <option value="Manager" {{ old('role') == 'Manager' ? 'selected' : '' }}>Supervisor</option>
                    <option value="HR Manager" {{ old('role') == 'HR Manager' ? 'selected' : '' }}>HR Supervisor</option>
                    <option value="Labor" {{ old('role') == 'Labor' ? 'selected' : '' }}>Labor</option>
                </select>
                @error('role') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Position <span class="text-red-500">*</span></label>
                <input type="text" name="position" value="{{ old('position') }}" required
                       class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('position') border-red-500 @enderror">
                @error('position') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Department</label>
                <input type="text" name="department" value="{{ old('department') }}"
                       class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" name="email" value="{{ old('role') === 'Labor' ? '' : old('email') }}"
                       class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                       class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <!-- Employee Type & Commission -->
        <div id="employee_type_fields" style="display: none;" class="bg-gray-50 p-6 rounded-lg mb-8 border">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Employee Type & Commission</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Employee Type <span class="text-red-500">*</span></label>
                    <select name="employee_type" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Type</option>
                        <option value="Sales" {{ old('employee_type') == 'Sales' ? 'selected' : '' }}>Sales</option>
                        <option value="Others" {{ old('employee_type') == 'Others' ? 'selected' : '' }}>Others</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Commission (%)</label>
                    <input type="number" step="0.01" name="employee_commission" value="{{ old('employee_commission') }}"
                           class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="e.g. 5.5">
                </div>
            </div>
        </div>

        <!-- Labor Fields -->
        <div id="labor_fields" style="display: none;" class="bg-amber-50 p-6 rounded-lg mb-8 border border-amber-200">
            <h3 class="text-lg font-semibold mb-4 text-amber-800">Labor Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Salary Basis</label>
                    <input type="text" name="salary_basis" value="{{ old('salary_basis') }}" placeholder="e.g. Per Piece / Daily"
                           class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Payment Method</label>
                    <select name="payment_method" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Select</option>
                        <option value="manual_bank_transfer" {{ old('payment_method') == 'manual_bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-medium mb-2">Labor Type</label>
                    <div class="space-y-3">
                        <div class="flex gap-3">
                            <select id="labor_type" name="labor_type" class="flex-1 p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Labor Type</option>
                                <option value="UPPER MAN">UPPER MAN</option>
                                <option value="BOTTOM MAN">BOTTOM MAN</option>
                                <option value="FINISH MAN">FINISH MAN</option>
                            </select>
                            <button type="button" id="add_labor_type" class="px-4 bg-blue-600 text-white rounded hover:bg-blue-700">+ Add</button>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" id="edit_labor_type" class="flex-1 px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">✎ Edit</button>
                            <button type="button" id="delete_labor_type" class="flex-1 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">✕ Delete</button>
                        </div>
                    </div>
                    <!-- Hidden input to store selected labor type for form submission -->
                    <input type="hidden" id="labor_type_hidden" name="labor_type_hidden" value="">
                </div>
            </div>
        </div>

        <!-- Salary & Hire Date -->
        <div id="regular_salary_fields" class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div>
                <label class="block text-gray-700 font-medium mb-2">Salary</label>
                <input type="number" step="0.01" name="salary" value="{{ old('salary') }}"
                       class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="0.00">
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">Currency</label>
                <input type="text" name="currency" value="{{ old('currency', 'INR') }}" maxlength="3"
                       class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 text-center uppercase">
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">Hire Date</label>
                <input type="date" name="hire_date" value="{{ old('hire_date') }}" max="{{ date('Y-m-d') }}"
                       class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <!-- Other Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="block text-gray-700 font-medium mb-2">Supervisor</label>
                <select name="manager_id" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Supervisor</option>
                    @foreach ($supervisors as $supervisor)
                        <option value="{{ $supervisor->id }}" {{ old('manager_id') == $supervisor->id ? 'selected' : '' }}>
                            {{ $supervisor->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">Emergency Contact</label>
                <input type="text" name="emergency_contact" value="{{ old('emergency_contact') }}"
                       class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">Date of Birth <span class="text-red-500">*</span></label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required
                       max="{{ date('Y-m-d') }}" min="1900-01-01"
                       class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">Aadhaar / PAN Number</label>
                <input type="text" name="aadhar_no" value="{{ old('aadhar_no') }}" maxlength="20"
                       class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <!-- Present Address Only -->
        <div class="bg-gray-50 p-6 rounded-lg mb-8 border">
            <h3 class="text-lg font-semibold mb-4">Present Residential Address</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <input type="text" name="present_address_line1" value="{{ old('present_address_line1') }}"
                           placeholder="Address Line 1" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <input type="text" name="present_city" value="{{ old('present_city', 'India') }}"
                           placeholder="City" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- Document Upload (Now 100% Working) -->
        <div class="bg-gray-50 p-6 rounded-lg mb-8 border">
            <h3 class="text-lg font-semibold mb-4">Upload Documents</h3>
            <div id="document_container" class="space-y-4"></div>
            <button type="button" id="add_document"
                    class="mt-4 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                + Add Document
            </button>
        </div>

        <!-- Submit -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button type="submit"
                    class="px-8 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700">
                Save and Continue
            </button>
            <a href="{{ route('employees.index') }}"
               class="px-8 py-3 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 text-center">
                Skip
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role');
    const employeeTypeFields = document.getElementById('employee_type_fields');
    const laborFields = document.getElementById('labor_fields');
    const regularSalaryFields = document.getElementById('regular_salary_fields');
    const emailFieldDiv = document.querySelector('input[name="email"]').closest('div');

    // Labor Types Management
    let laborTypes = ['UPPER MAN', 'BOTTOM MAN', 'FINISH MAN'];
    const laborTypeSelect = document.getElementById('labor_type');
    const laborTypeHidden = document.getElementById('labor_type_hidden');

    function updateLaborDropdown() {
        const currentValue = laborTypeSelect.value;
        laborTypeSelect.innerHTML = '<option value="">Select Labor Type</option>';
        laborTypes.forEach(t => {
            const option = document.createElement('option');
            option.value = t;
            option.textContent = t;
            if (t === currentValue) option.selected = true;
            laborTypeSelect.appendChild(option);
        });
    }

    function updateHiddenInput() {
        laborTypeHidden.value = laborTypeSelect.value;
    }

    updateLaborDropdown();

    // Sync hidden input when dropdown changes
    laborTypeSelect.addEventListener('change', updateHiddenInput);

    document.getElementById('add_labor_type').onclick = function(e) {
        e.preventDefault();
        const type = prompt('Enter new labor type:');
        
        if (!type) return; // User cancelled
        
        const normalizedType = type.trim().toUpperCase();
        
        if (!normalizedType) {
            alert('Labor type cannot be empty');
            return;
        }
        
        if (laborTypes.includes(normalizedType)) {
            alert(`"${normalizedType}" already exists in the list`);
            return;
        }
        
        laborTypes.push(normalizedType);
        laborTypes.sort();
        updateLaborDropdown();
        laborTypeSelect.value = normalizedType;
        updateHiddenInput();
        alert(`"${normalizedType}" added successfully`);
    };

    document.getElementById('edit_labor_type').onclick = function(e) {
        e.preventDefault();
        
        if (!laborTypeSelect.value) {
            alert('Please select a labor type first');
            return;
        }
        
        const oldName = laborTypeSelect.value;
        const newName = prompt(`Edit labor type:`, oldName);
        
        if (!newName) return; // User cancelled
        
        const normalizedName = newName.trim().toUpperCase();
        
        if (!normalizedName) {
            alert('Labor type cannot be empty');
            return;
        }
        
        if (normalizedName === oldName) {
            return; // No change
        }
        
        if (laborTypes.includes(normalizedName)) {
            alert(`"${normalizedName}" already exists in the list`);
            return;
        }
        
        const index = laborTypes.indexOf(oldName);
        if (index !== -1) {
            laborTypes[index] = normalizedName;
            laborTypes.sort();
            updateLaborDropdown();
            laborTypeSelect.value = normalizedName;
            updateHiddenInput();
            alert(`Renamed to "${normalizedName}" successfully`);
        }
    };

    document.getElementById('delete_labor_type').onclick = function(e) {
        e.preventDefault();
        
        if (!laborTypeSelect.value) {
            alert('Please select a labor type first');
            return;
        }
        
        if (confirm(`Delete "${laborTypeSelect.value}"?`)) {
            laborTypes = laborTypes.filter(t => t !== laborTypeSelect.value);
            laborTypeSelect.value = '';
            laborTypeHidden.value = '';
            updateLaborDropdown();
            alert('Labor type deleted successfully');
        }
    };

    // Fixed: Correct ID + Working Add Document
    document.getElementById('add_document').onclick = () => {
        const container = document.getElementById('document_container');
        const div = document.createElement('div');
        div.className = 'document_row bg-white p-5 rounded-lg border shadow-sm';
        div.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium mb-1">Document Name</label>
                    <input type="text" name="document_names[]" required placeholder="e.g. Aadhaar Card"
                           class="w-full p-2.5 border rounded focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">File(s)</label>
                    <input type="file" name="personal_documents[]" multiple class="w-full p-2 border rounded text-sm">
                </div>
                <div class="flex gap-2">
                    <button type="button" class="add_file bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700">
                        + More Files
                    </button>
                    <button type="button" class="remove_document bg-red-600 text-white px-4 py-2 rounded text-sm hover:bg-red-700">
                        Remove
                    </button>
                </div>
            </div>
            <div class="extra_files mt-3 ml-4"></div>
        `;
        container.appendChild(div);
    };

    // Event Delegation
    document.getElementById('document_container').onclick = function(e) {
        const row = e.target.closest('.document_row');
        if (!row) return;

        if (e.target.classList.contains('remove_document')) {
            if (confirm('Remove this document entry?')) row.remove();
        }
        if (e.target.classList.contains('add_file')) {
            const extra = row.querySelector('.extra_files');
            const input = document.createElement('div');
            input.className = 'mt-2';
            input.innerHTML = `<input type="file" name="personal_documents[]" class="w-full p-2 border rounded text-sm">`;
            extra.appendChild(input);
        }
    };

    // Role-based field toggle
    function toggleFields() {
        const role = roleSelect.value;
        employeeTypeFields.style.display = 'none';
        laborFields.style.display = 'none';
        regularSalaryFields.style.display = 'none';
        emailFieldDiv.style.display = 'block';

        document.querySelectorAll('#employee_type_fields select').forEach(el => el.removeAttribute('required'));

        if (role === 'Labor') {
            laborFields.style.display = 'block';
            emailFieldDiv.style.display = 'none';
        } else if (role === 'Employee') {
            employeeTypeFields.style.display = 'block';
            regularSalaryFields.style.display = 'grid';
            employeeTypeFields.querySelector('select[name="employee_type"]')?.setAttribute('required', 'required');
        } else if (role === 'Manager' || role === 'HR Manager') {
            regularSalaryFields.style.display = 'grid';
        }
    }

    roleSelect.addEventListener('change', toggleFields);
    toggleFields(); // Initial check
});
</script>
@endsection