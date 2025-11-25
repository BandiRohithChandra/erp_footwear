<div id="manager_form" class="hidden">
    <div>
        <label class="block text-gray-700 font-medium mb-2">Name*</label>
        <input type="text" name="name" value="{{ old('name') }}" class="w-full p-2 border rounded-lg">
    </div>

    <div>
        <label class="block text-gray-700 font-medium mb-2">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" class="w-full p-2 border rounded-lg">
    </div>

    <div>
        <label class="block text-gray-700 font-medium mb-2">Position*</label>
        <input type="text" name="position" value="{{ old('position') }}" class="w-full p-2 border rounded-lg">
    </div>

    <div>
        <label class="block text-gray-700 font-medium mb-2">Department</label>
        <input type="text" name="department" value="{{ old('department') }}" class="w-full p-2 border rounded-lg">
    </div>

    <div>
        <label class="block text-gray-700 font-medium mb-2">Phone</label>
        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full p-2 border rounded-lg">
    </div>

    <div>
        <label class="block text-gray-700 font-medium mb-2">Date of Birth*</label>
        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="w-full p-2 border rounded-lg">
    </div>

    <div>
        <label class="block text-gray-700 font-medium mb-2">Present Address*</label>
        <input type="text" name="present_address_line1" value="{{ old('present_address_line1') }}" class="w-full p-2 border rounded-lg mt-1" placeholder="Address Line 1">
        <input type="text" name="present_city" value="{{ old('present_city') }}" class="w-full p-2 border rounded-lg mt-1" placeholder="City">
        <input type="text" name="present_pin_code" value="{{ old('present_pin_code') }}" class="w-full p-2 border rounded-lg mt-1" placeholder="PIN Code">
    </div>

    <div>
        <label class="block text-gray-700 font-medium mb-2">Permanent Address*</label>
        <input type="text" name="permanent_address_line1" value="{{ old('permanent_address_line1') }}" class="w-full p-2 border rounded-lg mt-1" placeholder="Address Line 1">
        <input type="text" name="permanent_state" value="{{ old('permanent_state') }}" class="w-full p-2 border rounded-lg mt-1" placeholder="State">
        <input type="text" name="permanent_pin_code" value="{{ old('permanent_pin_code') }}" class="w-full p-2 border rounded-lg mt-1" placeholder="PIN Code">
    </div>
</div>
