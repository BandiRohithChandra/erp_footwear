{{-- Labor Fields --}}
<div>
    <div>
        <label for="name_labor" class="block text-gray-700 font-medium mb-2">Name*</label>
        <input id="name_labor" type="text" name="name" value="{{ old('name') }}" class="w-full p-2 border rounded-lg">
    </div>

    <div>
        <label for="position_labor" class="block text-gray-700 font-medium mb-2">Position*</label>
        <input id="position_labor" type="text" name="position" value="{{ old('position') }}" class="w-full p-2 border rounded-lg">
    </div>

    <div>
        <label for="salary_basis" class="block text-gray-700 font-medium mb-2">Salary Basis*</label>
        <select id="salary_basis" name="salary_basis" class="w-full p-2 border rounded-lg">
            <option value="">Select Basis</option>
            <option value="monthly" {{ old('salary_basis') == 'monthly' ? 'selected' : '' }}>Monthly</option>
            <option value="weekly" {{ old('salary_basis') == 'weekly' ? 'selected' : '' }}>Weekly</option>
            <option value="daily" {{ old('salary_basis') == 'daily' ? 'selected' : '' }}>Daily</option>
        </select>
    </div>

    <div>
        <label for="labor_type" class="block text-gray-700 font-medium mb-2">Labor Type*</label>
        <select id="labor_type" name="labor_type" class="w-full p-2 border rounded-lg">
            <option value="">Select Labor Type</option>
            <option value="Upper Labor" {{ old('labor_type') == 'Upper Labor' ? 'selected' : '' }}>Upper Labor</option>
            <option value="Lower Labor" {{ old('labor_type') == 'Lower Labor' ? 'selected' : '' }}>Lower Labor</option>
            <option value="Finish Labor" {{ old('labor_type') == 'Finish Labor' ? 'selected' : '' }}>Finish Labor</option>
        </select>
    </div>

    <div>
        <label for="labor_amount" class="block text-gray-700 font-medium mb-2">Amount*</label>
        <input type="number" step="0.01" id="labor_amount" name="labor_amount" value="{{ old('labor_amount') }}" class="w-full p-2 border rounded-lg">
    </div>

    <div>
        <label for="payment_method" class="block text-gray-700 font-medium mb-2">Payment Method*</label>
        <select id="payment_method" name="payment_method" class="w-full p-2 border rounded-lg">
            <option value="">Select Payment Method</option>
            <option value="manual_bank_transfer" {{ old('payment_method') == 'manual_bank_transfer' ? 'selected' : '' }}>Manual Bank Transfer</option>
            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
        </select>
    </div>

    <div>
        <label for="phone_labor" class="block text-gray-700 font-medium mb-2">Phone</label>
        <input id="phone_labor" type="text" name="phone" value="{{ old('phone') }}" class="w-full p-2 border rounded-lg">
    </div>

    <div>
        <label for="date_of_birth_labor" class="block text-gray-700 font-medium mb-2">Date of Birth*</label>
        <input id="date_of_birth_labor" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="w-full p-2 border rounded-lg">
    </div>

    <div>
        <label for="aadhar_no_labor" class="block text-gray-700 font-medium mb-2">Aadhaar / PAN Number*</label>
        <input id="aadhar_no_labor" type="text" name="aadhar_no" value="{{ old('aadhar_no') }}" class="w-full p-2 border rounded-lg" maxlength="20">
    </div>

    {{-- Present Address --}}
    <div>
        <label class="block text-gray-700 font-medium mb-2">Present Address*</label>
        <input id="present_address_line1_labor" type="text" name="present_address_line1" value="{{ old('present_address_line1') }}" placeholder="Address Line 1" class="w-full p-2 border rounded-lg mt-1">
        <input id="present_city_labor" type="text" name="present_city" value="{{ old('present_city') }}" placeholder="City" class="w-full p-2 border rounded-lg mt-1">
        <input id="present_pin_code_labor" type="text" name="present_pin_code" value="{{ old('present_pin_code') }}" placeholder="PIN Code" class="w-full p-2 border rounded-lg mt-1">
        <button type="button" id="copy_present_address_labor" class="mt-2 bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Copy Present Address</button>
    </div>

    {{-- Permanent Address --}}
    <div>
        <label class="block text-gray-700 font-medium mb-2">Permanent Address*</label>
        <input id="permanent_address_line1_labor" type="text" name="permanent_address_line1" value="{{ old('permanent_address_line1') }}" placeholder="Address Line 1" class="w-full p-2 border rounded-lg mt-1">
        <input id="permanent_state_labor" type="text" name="permanent_state" value="{{ old('permanent_state') }}" placeholder="State" class="w-full p-2 border rounded-lg mt-1">
        <input id="permanent_pin_code_labor" type="text" name="permanent_pin_code" value="{{ old('permanent_pin_code') }}" placeholder="PIN Code" class="w-full p-2 border rounded-lg mt-1">
    </div>

    {{-- Document Upload --}}
    <div class="mt-4 border p-4 rounded bg-gray-50">
        <h2 class="text-lg font-medium mb-2">Upload Documents</h2>
        <div id="document_container_labor">
            <div class="flex space-x-2 mb-2 document_row">
                <input type="text" name="document_names[]" placeholder="Document Name" class="p-2 border rounded w-1/2" />
                <input type="file" name="documents[]" class="p-2 border rounded w-1/2" />
                <button type="button" class="remove_document bg-red-500 text-white px-2 rounded">Delete</button>
            </div>
        </div>
        <button type="button" id="add_document_labor" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add More</button>
    </div>
</div>
