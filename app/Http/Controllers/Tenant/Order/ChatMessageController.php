<?php

namespace App\Http\Controllers\Tenant\Order;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Order\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated  = $request->validate([
            'message' => 'required|string',
            'chatroom_id' => 'required|numeric'
        ]);

        $chatMessage = ChatMessage::create([
            'customer_id' => 0,
            'message' => $validated['message'],
            'chatroom_id' => $validated['chatroom_id']
        ]);

        return response()->json(['data' => $chatMessage], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ChatMessage $chatMessage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChatMessage $chatMessage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChatMessage $chatMessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChatMessage $chatMessage)
    {
        //
    }

    public function customer_message(Request $request)
    {
        $customer = Auth::user();
        $validated = $request->validate(['message' => 'required|string', 'chatroom_id' => 'required|numeric']);
        $chat_message = ChatMessage::create(
            [
                'message' => $validated['message'],
                'customer_id' => $customer->id,
                'chatroom_id' => $validated['chatroom_id'],
            ]
        );

        return response()->json(['data' => $chat_message ], 201);
    }
}

