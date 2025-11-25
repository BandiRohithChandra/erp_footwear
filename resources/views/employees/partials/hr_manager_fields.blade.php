{{-- HR Manager Fields --}}
<div class="mb-4">
    <label class="block font-medium text-gray-700 mb-2">Full Name</label>
    <input type="text" name="name" value="{{ old('name') }}" class="w-full p-2 border rounded-lg" required>
</div>

<div class="mb-4">
    <label class="block font-medium text-gray-700 mb-2">Email</label>
    <input type="email" name="email" value="{{ old('email') }}" class="w-full p-2 border rounded-lg" required>
</div>

<div class="mb-4">
    <label class="block font-medium text-gray-700 mb-2">Phone</label>
    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full p-2 border rounded-lg" required>
</div>

<div class="mb-4">
    <label class="block font-medium text-gray-700 mb-2">Department</label>
    <input type="text" name="department" value="{{ old('department') }}" class="w-full p-2 border rounded-lg">
</div>

<div class="mb-4">
    <label class="block font-medium text-gray-700 mb-2">Address</label>
    <textarea name="address" class="w-full p-2 border rounded-lg">{{ old('address') }}</textarea>
</div>

{{-- Optional: Document Upload --}}
<div class="mb-4">
    <label class="block font-medium text-gray-700 mb-2">Upload Documents</label>
    <div id="document_container_hr_manager">
        <div class="flex space-x-2 mb-2 document_row">
            <input type="text" name="document_names[]" placeholder="Document Name" class="p-2 border rounded w-1/2" />
            <input type="file" name="documents[]" class="p-2 border rounded w-1/2" />
        </div>
    </div>
    <button type="button" id="add_document_hr_manager" class="bg-blue-500 text-white px-4 py-2 rounded">Add Document</button>
</div>
