<x-guest-layout>
<style>
/* ===== Container ===== */
.login-page {
    display: flex;
    justify-content: center;
    width: 100%;
    min-height: 100vh;
    background: #f9f9f9;
    font-family: 'Poppins', sans-serif;
    padding: 20px;
    box-sizing: border-box;
}

/* Scrollable wrapper for the form */
.right-side-wrapper {
    width: 100%;
    max-width: 600px;
    background: #ffffff;
    max-height: 100vh;
    overflow-y: auto;
    display: flex
;
    justify-content: center;
}

/* ===== Right Side Signup ===== */
.right-side {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 40px 30px;
    border-radius: 15px;
    box-sizing: border-box;
}

.right-side .logo-top {
    width: 178px;
    max-width: 100%;
    height: auto;
    margin-bottom: 30px;
}

/* Form Row & Group */
.form-row {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.form-group {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0; /* prevent overflow on small screens */
}

.form-group label {
    font-size: 16px;
    font-weight: 500;
    margin-bottom: 6px;
    color: #4B5563;
}

.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="password"],
.form-group input[type="file"],
.form-group select {
    padding: 10px 14px;
    font-size: 15px;
    border-radius: 10px;
    border: 1px solid #d1d5db;
    transition: all 0.3s ease;
    width: 100%;
    box-sizing: border-box;
}

.form-group input:focus, 
.form-group select:focus {
    outline: none;
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79,70,229,0.2);
}

/* Sign Up Button */
.signup-btn-form {
    width: 100%;
    max-width: 250px;
    padding: 12px 0;
    border-radius: 30px;
    background: linear-gradient(90deg, #FF7A00 0%, #FFCF00 100%);
    color: #fff;
    font-size: 18px;
    font-weight: 500;
    border: none;
    cursor: pointer;
    margin: 20px 0;
    transition: transform 0.2s;
}

.signup-btn-form:hover {
    transform: scale(1.05);
}

/* Text Link */
.text-link {
    display: inline-block;
    margin-top: 10px;
    color: #6b7280;
    text-decoration: none;
    font-size: 15px;
}

.text-link:hover {
    color: #4f46e5;
}

/* ===== Mobile Responsive ===== */
@media (max-width: 768px) {
    .right-side-wrapper { max-height: 100vh; }
    .right-side { padding: 30px 20px; }
    .logo-top { width: 150px; margin-bottom: 20px; }
    .form-row { flex-direction: column; gap: 15px; }
    .form-group input, .form-group select { font-size: 14px; padding: 10px 12px; }
    .signup-btn-form { font-size: 16px; height: 45px; }
    .text-link { font-size: 14px; }
}

@media (max-width: 480px) {
    .logo-top { width: 120px; margin-bottom: 15px; }
    .form-group input, .form-group select { font-size: 13px; padding: 8px 10px; }
    .signup-btn-form { font-size: 14px; height: 40px; }
    .text-link { font-size: 12px; }
}
</style>

<div class="login-page">
    <div class="right-side-wrapper">
        <div class="right-side">
            <img src="{{ asset('storage/login/LOGO MARIX.png') }}" alt="RMZ Logo" class="logo-top">

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" id="registerForm" action="{{ route('register') }}" enctype="multipart/form-data" style="width:100%;">
                @csrf

                <!-- Name & Email -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Name <span class="text-red-500">*</span></label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="email">Email <span class="text-red-500">*</span></label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                    </div>
                </div>

                <!-- Phone & Address -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone <span class="text-red-500">*</span></label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address <span class="text-red-500">*</span></label>
                        <input id="address" type="text" name="address" value="{{ old('address') }}" required>
                    </div>
                </div>

                <!-- Category -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="category">Category <span class="text-red-500">*</span></label>
                        <select id="category" name="category" required>
                            <option value="">-- Select --</option>
                            <option value="retail" {{ old('category') == 'retail' ? 'selected' : '' }}>Retail</option>
                            <option value="wholesale" {{ old('category') == 'wholesale' ? 'selected' : '' }}>Wholesale</option>
                        </select>
                    </div>
                </div>

                <!-- Password -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password <span class="text-red-500">*</span></label>
                        <input id="password" type="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password <span class="text-red-500">*</span></label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required>
                    </div>
                </div>

                <!-- Business & GST -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="business_name">Business Name <span class="text-red-500">*</span></label>
                        <input id="business_name" type="text" name="business_name" value="{{ old('business_name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="gst_no">GST Number <span class="text-red-500">*</span></label>
                        <input id="gst_no" type="text" name="gst_no" value="{{ old('gst_no') }}" required>
                    </div>
                </div>

                <!-- Company & GST Certificate -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="company_document">Company Certificate <span class="text-red-500">*</span></label>
                        <input type="file" name="company_document" id="company_document" required>
                    </div>
                    <div class="form-group">
                        <label for="gst_certificate">GST Certificate <span class="text-red-500">*</span></label>
                        <input type="file" name="gst_certificate" id="gst_certificate" required>
                    </div>
                </div>

                <!-- Aadhar -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="aadhar_number">Aadhar Number <span class="text-red-500">*</span></label>
                        <input id="aadhar_number" type="text" name="aadhar_number" value="{{ old('aadhar_number') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="aadhar_certificate">Aadhar Certificate <span class="text-red-500">*</span></label>
                        <input type="file" name="aadhar_certificate" id="aadhar_certificate" required>
                    </div>
                </div>

                <!-- Electricity Certificate -->
                <div class="form-row">
                    <div class="form-group" style="flex: 1;">
                        <label for="electricity_certificate">Electricity Bill <span class="text-red-500">*</span></label>
                        <input type="file" name="electricity_certificate" id="electricity_certificate" required>
                    </div>
                </div>

                <!-- Disclaimer -->
                <p style="font-size: 12px; color: #6b7280; margin-top: 10px; line-height: 1.4;">
                    ⚠️ Disclaimer: Any false, misleading, or fraudulent information submitted through Marix may result in rejection of your application by the client company, suspension of access, and/or legal action as per applicable law.
                </p>

                <!-- Submit Button -->
                <button type="submit" class="signup-btn-form">Sign Up</button>
                <a href="{{ route('login') }}" class="text-link">Already registered? Log in</a>
            </form>
        </div>
    </div>
</div>
</x-guest-layout>
