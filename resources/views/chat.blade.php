@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-12 p-6 bg-white rounded-xl shadow">
    <h2 class="text-xl font-semibold mb-4">ERP Chatbot 🤖</h2>

    <div id="chat-container" class="border rounded p-4 h-64 overflow-y-auto mb-4 bg-gray-50">
        <div id="messages"></div>
    </div>

    <div class="flex gap-2">
        <input type="text" id="user-input" class="flex-1 border rounded px-3 py-2" placeholder="Ask me something...">
        <button id="send-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 rounded">Send</button>
    </div>
</div>

<script>
document.getElementById('send-btn').addEventListener('click', async () => {
    const input = document.getElementById('user-input');
    const message = input.value.trim();
    if(!message) return;

    const messages = document.getElementById('messages');
    messages.innerHTML += `<div class="mb-2"><strong>You:</strong> ${message}</div>`;

    const res = await fetch("{{ route('chat.respond') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({message})
    });
    const data = await res.json();
    messages.innerHTML += `<div class="mb-2"><strong>Bot:</strong> ${data.message}</div>`;
    
    // Scroll to bottom
    const container = document.getElementById('chat-container');
    container.scrollTop = container.scrollHeight;

    input.value = '';
});

// Press Enter to send
document.getElementById('user-input').addEventListener('keypress', function(e) {
    if(e.key === 'Enter') document.getElementById('send-btn').click();
});
</script>
@endsection
