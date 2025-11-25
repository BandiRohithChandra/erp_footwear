{{-- resources/views/partials/chatbot.blade.php --}}

@php
    $chatbotRoute = route('chatbot.ask'); 
    $csrfToken = csrf_token();
@endphp

<div id="chatbot" class="fixed bottom-5 right-5 w-80 h-96 bg-white shadow-xl rounded-xl flex flex-col overflow-hidden z-[9999]">
    {{-- Header --}}
    <div class="bg-blue-600 text-white px-4 py-2 font-semibold flex justify-between items-center">
        ERP Chatbot
        <button id="chatbot-close" class="text-white text-lg">&times;</button>
    </div>

    {{-- Messages --}}
    <div id="chatbot-messages" class="flex-1 p-3 overflow-y-auto text-sm">
        <div class="text-gray-500 text-center mt-2">Chatbot loaded! Type a message below.</div>
    </div>

    {{-- Input --}}
    <div class="p-2 border-t border-gray-200 flex">
        <input id="chatbot-input" type="text" placeholder="Type a message..." class="flex-1 px-3 py-2 border rounded-l-lg focus:outline-none">
        <button id="chatbot-send" class="bg-blue-600 text-white px-4 rounded-r-lg hover:bg-blue-700">Send</button>
    </div>
</div>

{{-- Floating Button --}}
<button id="chatbot-open" class="fixed bottom-5 right-5 bg-blue-600 text-white px-4 py-2 rounded-full shadow-lg hover:bg-blue-700 z-[9999]">
💬 Chat
</button>

<style>
    #chatbot { display: flex; } /* always visible for testing */
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatbot = document.getElementById('chatbot');
    const chatbotOpen = document.getElementById('chatbot-open');
    const chatbotClose = document.getElementById('chatbot-close');
    const chatbotSend = document.getElementById('chatbot-send');
    const chatbotInput = document.getElementById('chatbot-input');
    const chatbotMessages = document.getElementById('chatbot-messages');

    const route = @json($chatbotRoute);
    const csrfToken = @json($csrfToken);

    chatbotClose.addEventListener('click', () => {
        chatbot.style.display = 'none';
        chatbotOpen.style.display = 'block';
    });

    chatbotOpen.addEventListener('click', () => {
        chatbot.style.display = 'flex';
        chatbotOpen.style.display = 'none';
    });

    async function sendMessage() {
        const msg = chatbotInput.value.trim();
        if (!msg) return;

        chatbotMessages.innerHTML += `<div class="text-right mb-2"><span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-lg">${msg}</span></div>`;
        chatbotInput.value = '';
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;

        // Dummy reply
        chatbotMessages.innerHTML += `<div class="text-left mb-2"><span class="inline-block bg-gray-100 text-gray-900 px-3 py-1 rounded-lg">Bot reply: ${msg}</span></div>`;
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }

    chatbotSend.addEventListener('click', sendMessage);
    chatbotInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') sendMessage();
    });
});
</script>
