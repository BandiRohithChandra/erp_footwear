@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Tabs -->
    <div class="flex space-x-4 border-b mb-6">
        <button type="button" id="tab-profile" class="py-2 px-4 border-b-2 border-blue-500 font-medium">Profile</button>
        <button type="button" id="tab-company" class="py-2 px-4 border-b-2 border-transparent font-medium">Company</button>
    </div>

    <!-- Profile Section -->
    <section id="profile-content">
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{ __('Update Profile') }}</h2>
            <p class="mt-1 text-sm text-gray-600">{{ __('Update your personal profile details.') }}</p>
        </header>
@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
            @csrf
            @method('PATCH')

            @include('profile.partials.profile-fields', ['user' => $user])

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Save Changes') }}</x-primary-button>
            </div>
        </form>
    </section>

   <!-- Company Section -->
<!-- Company Section -->
<section id="company-content" class="hidden">
    <header>
        <h2 class="text-lg font-medium text-gray-900">{{ __('Company Information') }}</h2>
        <p class="mt-1 text-sm text-gray-600">{{ __('Update your company details and documents.') }}</p>
    </header>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('PATCH')

        <div class="bg-white p-6 rounded-lg shadow space-y-4">
            <h2 class="text-xl font-bold mb-4">Company Information</h2>

            <!-- Company Name -->
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
            <!-- <div>
                <label for="category" class="block text-gray-700 font-medium mb-2">Category</label>
                <select name="category" id="category" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Category</option>
                    <option value="wholesale" {{ old('category', $user->category) == 'wholesale' ? 'selected' : '' }}>Wholesale</option>
                    <option value="retail" {{ old('category', $user->category) == 'retail' ? 'selected' : '' }}>Retail</option>
                </select>
            </div> -->


            <!-- State -->
<div>
    <label for="state" class="block text-gray-700 font-medium mb-2">State</label>
    <input id="state" type="text" name="state" value="{{ old('state', $user->state) }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
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

            <!-- Website -->
            <div>
                <label for="website" class="block text-gray-700 font-medium mb-2">Website</label>
                <input id="website" type="url" name="website" value="{{ old('website', $user->website) }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Contact Person -->
            <div>
                <label for="contact_person" class="block text-gray-700 font-medium mb-2">Contact Person</label>
                <input id="contact_person" type="text" name="contact_person" value="{{ old('contact_person', $user->contact_person) }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Designation -->
            <div>
                <label for="designation" class="block text-gray-700 font-medium mb-2">Designation</label>
                <input id="designation" type="text" name="designation" value="{{ old('designation', $user->designation) }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Alternate Email -->
            <div>
                <label for="alt_email" class="block text-gray-700 font-medium mb-2">Alternate Email</label>
                <input id="alt_email" type="email" name="alt_email" value="{{ old('alt_email', $user->alt_email) }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Alternate Phone -->
            <div>
                <label for="alt_phone" class="block text-gray-700 font-medium mb-2">Alternate Phone</label>
                <input id="alt_phone" type="text" name="alt_phone" value="{{ old('alt_phone', $user->alt_phone) }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save Changes') }}</x-primary-button>
        </div>
    </form>
</section>


</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const profileContent = document.getElementById('profile-content');
    const companyContent = document.getElementById('company-content');
    const profileTab = document.getElementById('tab-profile');
    const companyTab = document.getElementById('tab-company');

    if (!profileContent || !companyContent || !profileTab || !companyTab) return;

    function switchTab(tab) {
        profileContent.classList.add('hidden');
        companyContent.classList.add('hidden');
        profileTab.classList.remove('border-blue-500');
        companyTab.classList.remove('border-blue-500');
        profileTab.classList.add('border-transparent');
        companyTab.classList.add('border-transparent');

        if (tab === 'profile') {
            profileContent.classList.remove('hidden');
            profileTab.classList.add('border-blue-500');
        } else {
            companyContent.classList.remove('hidden');
            companyTab.classList.add('border-blue-500');
        }
    }

    profileTab.addEventListener('click', () => switchTab('profile'));
    companyTab.addEventListener('click', () => switchTab('company'));

    switchTab('profile'); // default
});
</script>
@endsection
