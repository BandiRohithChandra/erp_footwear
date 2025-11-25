<x-guest-layout>
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-10 rounded-lg shadow-lg text-center max-w-md">
        <h1 class="text-3xl font-bold mb-4">Thank You!</h1>
        <p class="text-gray-700">
            Thank you for reaching out to us. Your registration has been received.
            Our admin will review your application, and you will be notified once your account is approved.
        </p>
        <a href="{{ route('login') }}" class="mt-6 inline-block bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600">
            Go to Login
        </a>
    </div>
</div>
</x-guest-layout>
