 <!-- Profile Information -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-bold mb-4">Profile Information</h2>
        <p class="text-sm text-gray-600 mb-6">Update your account's profile information and email address.</p>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PATCH')

            <!-- Name -->
            <div>
                <label for="name" class="block text-gray-700 font-medium mb-2">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-gray-700 font-medium mb-2">Phone</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Country -->
            <div>
                <label for="country" class="block text-gray-700 font-medium mb-2">Country</label>
                <input id="country" type="text" name="country" value="{{ old('country', $user->country) }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-gray-700 font-medium mb-2">Address</label>
                <textarea id="address" name="address" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('address', $user->address) }}</textarea>
            </div>

            <!-- Profile Picture -->
            <div>
                <label for="profile_picture" class="block text-gray-700 font-medium mb-2">Profile Picture</label>
                <div class="flex items-center space-x-4">
                    @if($user->profile_picture)
                        <img src="{{ Storage::url($user->profile_picture) }}" class="w-16 h-16 rounded-full object-cover mb-2">
                    @endif
                    <input id="profile_picture" type="file" name="profile_picture" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-500">
                    Save
                </button>
            </div>
        </form>
    </div>

    <!-- Update Password -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-bold mb-4">Update Password</h2>
        <p class="text-sm text-gray-600 mb-6">Ensure your account is using a long, random password to stay secure.</p>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Current Password -->
            <div>
                <label for="current_password" class="block text-gray-700 font-medium mb-2">Current Password</label>
                <input id="current_password" type="password" name="current_password" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- New Password -->
            <div>
                <label for="password" class="block text-gray-700 font-medium mb-2">New Password</label>
                <input id="password" type="password" name="password" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-500">
                    Update Password
                </button>
            </div>
        </form>
    </div>

    <!-- Delete Account -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-bold mb-4">Delete Account</h2>
        <p class="text-sm text-gray-600 mb-6">Once your account is deleted, all of its resources and data will be permanently deleted.</p>

        <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4">
            @csrf
            @method('DELETE')

            <!-- Password -->
            <div>
                <label for="delete_password" class="block text-gray-700 font-medium mb-2">Password</label>
                <input id="delete_password" type="password" name="password" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-red-500" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 focus:ring-2 focus:ring-red-500" onclick="return confirm('Are you sure you want to delete your account?')">
                Delete Account
            </button>
        </form>
    </div>