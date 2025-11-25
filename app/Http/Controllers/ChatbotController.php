<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        $message = $request->input('message');

        // Free/OpenAI-free simulation: simple keyword-based response
        $reply = "I am your ERP assistant. You said: " . $message;

        return response()->json(['reply' => $reply]);
    }
}
