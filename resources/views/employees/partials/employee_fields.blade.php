{{-- Employee Fields --}}
<div>
    <div>
        <label for="name" class="block text-gray-700 font-medium mb-2">Name*</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" class="w-full p-2 border rounded-lg">
    </div>

    <div>
        <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" class="w-full p-2 border rounded-lg">
    </div>

    <div>
        <label for="position" class="block text-gray-700 font-medium mb-2">Position*</label>
        <input id="position" type="text" name="position" value="{{ old('position') }}" class="w-full p-2 border rounded-lg">
    </div>

    <div>
        <label for="department" class="block text-gray-700 font-medium mb-2">Department*</label>
        <input id="department" type="text" name="department" value="{{ old('department') }}" class="w-full p-2 border rounded-lg">
    </div>

    <div>
        <label for="employee_type" class="block text-gray-700 font-medium mb-2">Employee Type*</label>
        <select id="employee_type" name="employee_type" class="w-full p-2 border rounded-lg">
            <option value="">Select Type</option>
            <option value="Permanent" {{ old('employee_type') == 'Permanent' ? 'selected' : '' }}>Sales</option>
            <option value="Contract" {{ old('employee_type') == 'Contract' ? 'selected' : '' }}>Others</option>
        </select>
    </div>

    <div>
        <label for="employee_salary" class="block text-gray-700 font-medium mb-2">Salary*</label>
        <input type="number" step="0.01" id="employee_salary" name="employee_salary" value="{{ old('employee_salary') }}" class="w-full p-2 border rounded-lg">
    </div>

    <div>
        <label for="employee_commission" class="block text-gray-700 font-medium mb-2">Commission (%)</label>
        <input type="number" step="0.01" id="employee_commission" name="employee_commission" value="{{ old('employee_commission') }}" class="w-full p-2 border rounded-lg">
    </div>

    <div>
        <label for="phone" class="block text-gray-700 font-medium mb-2">Phone</label>
        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" class="w-full p-2 border rounded-lg">
    </div>

    <div>
        <label for="emergency_contact" class="block text-gray-700 font-medium mb-2">Emergency Contact</label>
        <input id="emergency_contact" type="text" name="emergency_contact" value="{{ old('emergency_contact') }}" class="w-full p-2 border rounded-lg">
    </div>

    <div>
        <label for="date_of_birth" class="block text-gray-700 font-medium mb-2">Date of Birth*</label>
        <input id="date_of_birth" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="w-full p-2 border rounded-lg">
    </div>

    <div>
        <label for="aadhar_no" class="block text-gray-700 font-medium mb-2">Aadhaar / PAN Number*</label>
        <input id="aadhar_no" type="text" name="aadhar_no" value="{{ old('aadhar_no') }}" class="w-full p-2 border rounded-lg" maxlength="20">
    </div>

    {{-- Present Address --}}
    <div>
        <label class="block text-gray-700 font-medium mb-2">Present Address*</label>
        <input id="present_address_line1" type="text" name="present_address_line1" value="{{ old('present_address_line1') }}" placeholder="Address Line 1" class="w-full p-2 border rounded-lg mt-1">
        <input id="present_city" type="text" name="present_city" value="{{ old('present_city') }}" placeholder="City" class="w-full p-2 border rounded-lg mt-1">
        <input id="present_pin_code" type="text" name="present_pin_code" value="{{ old('present_pin_code') }}" placeholder="PIN Code" class="w-full p-2 border rounded-lg mt-1">
        <button type="button" id="copy_present_address" class="mt-2 bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Copy Present Address</button>
    </div>

    {{-- Permanent Address --}}
    <div>
        <label class="block text-gray-700 font-medium mb-2">Permanent Address*</label>
        <input id="permanent_address_line1" type="text" name="permanent_address_line1" value="{{ old('permanent_address_line1') }}" placeholder="Address Line 1" class="w-full p-2 border rounded-lg mt-1">
        <input id="permanent_state" type="text" name="permanent_state" value="{{ old('permanent_state') }}" placeholder="State" class="w-full p-2 border rounded-lg mt-1">
        <input id="permanent_pin_code" type="text" name="permanent_pin_code" value="{{ old('permanent_pin_code') }}" placeholder="PIN Code" class="w-full p-2 border rounded-lg mt-1">
    </div>

    {{-- Document Upload --}}
    <div class="mt-4 border p-4 rounded bg-gray-50">
        <h2 class="text-lg font-medium mb-2">Upload Documents</h2>
        <div id="document_container">
            <div class="flex space-x-2 mb-2 document_row">
                <input type="text" name="document_names[]" placeholder="Document Name" class="p-2 border rounded w-1/2" />
                <input type="file" name="documents[]" class="p-2 border rounded w-1/2" />
                <button type="button" class="remove_document bg-red-500 text-white px-2 rounded">Delete</button>
            </div>
        </div>
        <button type="button" id="add_document" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add More</button>
    </div>
</div>
