

<div class="bg-white p-6 rounded-lg shadow space-y-4">
    <h2 class="text-xl font-bold mb-4">Company Information</h2>

    <!-- Business Name -->
    <div>
        <label for="business_name" class="block text-gray-700 font-medium mb-2">Company Name</label>
        <input id="business_name" type="text" name="business_name" value="{{ old('business_name', $user->business_name) }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
    </div>

    <!-- Company Document -->
    <div>
        <label for="company_document" class="block text-gray-700 font-medium mb-2">Company Document</label>
        <div class="flex items-center space-x-4">
            @if (!empty($user->company_document))
                <a href="{{ Storage::url($user->company_document) }}" target="_blank" class="text-blue-500 underline">View Document</a>
            @endif
            <input id="company_document" type="file" name="company_document" class="p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
    </div>

    <!-- GST Number -->
    <div>
        <label for="gst_no" class="block text-gray-700 font-medium mb-2">GST Number</label>
        <input id="gst_no" type="text" name="gst_no" value="{{ old('gst_no', $user->gst_no) }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
    </div>

    <!-- Category -->
    <div>
        <label for="category" class="block text-gray-700 font-medium mb-2">Category</label>
        <select name="category" id="category" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            <option value="">Select Category</option>
            <option value="wholesale" {{ old('category', $user->category) == 'wholesale' ? 'selected' : '' }}>Wholesale</option>
            <option value="retail" {{ old('category', $user->category) == 'retail' ? 'selected' : '' }}>Retail</option>
        </select>
    </div>

    <!-- Address -->
    <div>
        <label for="address" class="block text-gray-700 font-medium mb-2">Address</label>
        <textarea id="address" name="address" rows="3" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('address', $user->address) }}</textarea>
    </div>

    <!-- Phone -->
    <div>
        <label for="phone" class="block text-gray-700 font-medium mb-2">Phone</label>
        <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
    </div>

    <!-- Email -->
    <div>
        <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
    </div>
</div>
