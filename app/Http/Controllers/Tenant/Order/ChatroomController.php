<?php

namespace App\Http\Controllers\Tenant\Order;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Order\Chatroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chatrooms = Chatroom::with('order')->get();
        return ['data' => $chatrooms];
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Chatroom $chatroom, string $id)
    {
        $chatroom = Chatroom::with('chat_messages')->findOrFail($id);

        return response()->json(['data' => $chatroom], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chatroom $chatroom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chatroom $chatroom)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chatroom $chatroom)
    {
        //
    }

    public function customer_chatrooms(Request $request)
    {
        $customer = Auth::user();
        $chatrooms = Chatroom::where('customer_id', $customer->id)->with('order:id,title')->get();

        return response()->json(['data' => $chatrooms], 200);

    }

    public function customer_chatroom(Request $request, string $id)
    {
        $customer = Auth::user();
        $chatroom = Chatroom::with('chat_messages')->where('id', $id)->where('customer_id', $customer->id)->first();

        return response()->json(['data' => $chatroom], 200);
    }
}
