<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 bg-white rounded-2xl shadow-lg">
        {{-- Header --}}
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-indigo-600">Forgot Password</h1>
            <p class="text-gray-600 mt-2 text-sm">
                Enter your email and we will send you a link to reset your password.
            </p>
        </div>

        {{-- Session Status --}}
        @if (session('status'))
            <div class="mb-4 p-3 text-green-800 bg-green-100 rounded">
                {{ session('status') }}
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-4 p-3 text-red-800 bg-red-100 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            {{-- Email --}}
            <div>
                <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
            </div>

            {{-- Submit --}}
            <div class="mt-6">
                <button type="submit"
                    class="w-full bg-indigo-600 text-white py-2 rounded-lg font-semibold hover:bg-indigo-700 transition duration-200">
                    Send Password Reset Link
                </button>
            </div>
        </form>

        {{-- Back to login --}}
        <div class="mt-4 text-center text-sm">
            <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Back to Login</a>
        </div>
    </div>
</div>
</x-guest-layout>
