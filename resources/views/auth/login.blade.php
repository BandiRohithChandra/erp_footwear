<x-guest-layout>
<style>
/* ===== Container ===== */
.login-page {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100vw;
    height: 100vh;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

/* ===== Right Side Login Professional UI ===== */
.right-side {
    width: 100%;
    max-width: 420px;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(15px);
    border-radius: 25px;
    padding: 40px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}
.right-side:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 50px rgba(0,0,0,0.15);
}

/* Logo */
.right-side .logo-top {
    width: 147px;
    display: block;
    margin: 0 auto 20px auto;
}

/* Form Inputs */
.input-group {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 15px;
    width: 100%;
}
.input-group img { width: 22px; height: 22px; }
.input-group label { font-size: 16px; font-weight: 500; color: #495057; }

/* Text Inputs */
x-text-input {
    width: 100%;
    padding: 14px 16px;
    border-radius: 12px;
    border: 1px solid #ced4da;
    font-size: 15px;
    transition: border-color 0.3s, box-shadow 0.3s;
}
x-text-input:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,0.2);
}

/* Show Password Toggle */
.password-wrapper {
    position: relative;
    width: 100%;
}
.password-wrapper .toggle-password {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    font-size: 14px;
    color: #6366f1;
}

/* Remember + Forgot */
.forgot-login {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 15px 0 25px 0;
}
.forgot-login a {
    font-size: 14px;
    color: #4f46e5;
    text-decoration: none;
    transition: color 0.3s;
}
.forgot-login a:hover { color: #3730a3; }

/* Remember Checkbox */
.remember-label {
    display: flex; align-items: center; gap: 8px;
    font-size: 14px; color: #495057;
}
.remember-checkbox {
    width: 18px; height: 18px;
    accent-color: #6366f1;
    cursor: pointer;
}

/* Login Button */
#loginBtn {
    width: 100%;
    padding: 14px 0;
    border-radius: 30px;
    border: none;
    font-size: 16px;
    font-weight: 600;
    color: #fff;
    background: linear-gradient(90deg, #4f46e5 0%, #6366f1 100%);
    cursor: pointer;
    transition: background 0.3s, transform 0.2s;
}
#loginBtn:hover {
    transform: scale(1.03);
    background: linear-gradient(90deg, #3730a3 0%, #4f46e5 100%);
}

/* Signup Button */
.signup-btn-mobile {
    display: block;
    width: 100%;
    text-align: center;
    padding: 14px 0;
    border-radius: 30px;
    font-size: 16px;
    font-weight: 600;
    color: #000;
    background: #fff;
    border: 2px solid #6366f1;
    margin-top: 20px;
    text-decoration: none;
}

/* Terms Link */
.terms-link { margin-top: 20px; text-align: center; }
.terms-link a { font-size: 14px; color: #555; text-decoration: underline; }

</style>

<div class="login-page" style="
    margin: -24px;
">
    <div class="right-side">
        <img src="{{ asset('storage/login/LOGO MARIX.png') }}" alt="RMZ Logo" class="logo-top">

        <form id="loginForm" method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="input-group">
                <img src="{{ asset('storage/login/email.png') }}" alt="Email Icon">
                <label for="email">Email</label>
            </div>
            <x-input-error :messages="$errors->get('email')" class="mb-2" />
            <x-text-input 
                id="email" type="email" name="email" 
                :value="old('email')" placeholder="Enter your email" 
                required autofocus
                style="width:100%;"
            />

            {{-- Password --}}
            <div class="input-group" style="margin-top:15px;">
                <img src="{{ asset('storage/login/password.png') }}" alt="Password Icon">
                <label for="password">Password</label>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mb-2" />
            <x-text-input 
                id="password" type="password" name="password" 
                placeholder="Enter your password" required
                style="width:100%;"
            />

            {{-- Show/Hide Password --}}
            <div style="text-align:right; margin-top:5px;">
                <button type="button" class="toggle-password" onclick="togglePassword()" 
                    style="background:none; border:none; color:#6366f1; cursor:pointer; font-weight:500;">
                    Show
                </button>
            </div>

            {{-- Remember + Forgot --}}
            <div class="forgot-login">
                <label class="remember-label">
                    <input type="checkbox" name="remember" class="remember-checkbox"> Remember Me
                </label>
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Forgot Password?</a>
                @endif
            </div>

            {{-- Login Button --}}
            <button id="loginBtn" type="submit">Login</button>

            {{-- Signup Button --}}
            <a href="{{ route('register') }}" class="signup-btn-mobile">Signup</a>
        </form>

        {{-- Terms Link --}}
        <div class="terms-link">
            <a href="{{ route('terms') }}" target="_blank">Terms & Conditions</a>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleBtn = document.querySelector('.toggle-password');
    if(passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleBtn.textContent = 'Hide';
    } else {
        passwordInput.type = 'password';
        toggleBtn.textContent = 'Show';
    }
}
</script>
</x-guest-layout>
