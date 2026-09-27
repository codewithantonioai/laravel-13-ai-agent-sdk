<?php

namespace App\Http\Controllers;

use App\Ai\Agents\NotesAssistant;
use Illuminate\Http\Request;

class AssistantController extends Controller
{
    public function index(Request $request)
    {
        $conversation = $request->user()->conversations()
            ->find($request->session()->get('conversation_id'));

        return view('assistant', [
            'messages' => $conversation?->messages()->oldest()->get() ?? [],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(['question' => ['required', 'string', 'max:500']]);

        $user = $request->user();

        $conversation = $user->conversations()
            ->find($request->session()->get('conversation_id'));

        $response = NotesAssistant::make(user: $user)
            ->continueOrStart($conversation?->id, as: $user)
            ->prompt($request->input('question'));

        $request->session()->put('conversation_id', $response->conversationId);

        return to_route('assistant');
    }
}
