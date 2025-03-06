<?php

namespace App\Http\Controllers\Tenant\Order;

use App\Http\Controllers\Controller;
use App\Http\Services\ZoomService;
use App\Models\Tenant\Consultation\Consultation;
use App\Models\Tenant\Order\Chatroom;
use App\Models\Tenant\Order\Order;
use App\Models\Tenant\Order\ZoomMeeting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stancl\Tenancy\Database\Models\Domain;

class OrderController extends Controller
{
    private $zoomService;

    public function __construct(ZoomService $zoomService)
    {
        $this->zoomService = $zoomService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orders = Order::with('chatroom', 'zoom_meeting')->filter($request)->latest()->paginate($request->query('per_page', 10));
        return response()->json(['data' => $orders], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $customer = Auth::user();

        if(!$customer) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $validated = $request->validate([
            'consultation_id' => 'required|numeric',
        ]);

        $consultation = Consultation::find($validated['consultation_id']);

        $order = Order::create([
            'customer_id' => $customer['id'],
            'consultation_id' => $validated['consultation_id'],
            'type' => $consultation['type'],
            'title' => $consultation['title'],
            'customer_name' => $customer['name'],
            'paid_amount' => $consultation['price'],
            'start_date' => $consultation['start_date'],
            'start_time' => $consultation['start_time'],
            'is_active' => true,
        ]);

        if($order->type === 'إستشارة نصية') {
            Chatroom::create([
                'order_id' => $order['id'],
                'customer_id' => $customer['id'],
            ]);
        }

         if ($order->type === 'جلسة عن بعد') {
            // Extract only the date part from start_date
            $date = Carbon::parse($order['start_date'])->format('Y-m-d'); // "2025-03-07"

            // Combine date with time
            $dateTimeString = "{$date} {$order['start_time']}"; // "2025-03-07 22:00:00"

            // Parse to ISO 8601 format for Zoom
            $startTime = Carbon::parse($dateTimeString)
                ->setTimezone('UTC') // Convert to UTC for Zoom API
                ->toIso8601String();

            $meeting = $this->zoomService->scheduleMeeting(
                topic: $order['title'],
                startTime: $startTime
            );

            ZoomMeeting::create([
                'customer_id' => $customer['id'],
                'order_id' => $order['id'],
                'start_url' => $meeting['start_url'],
                'join_url' => $meeting['join_url'],
            ]);
        }

        return response()->json(['message' => 'تم شراء الخدمة بنجاح.'], 201);


    }

    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);
        $validated = $request->validate([
            'is_active' => 'boolean',
        ]);

        $order->update([
            'is_active' => $validated['is_active'],
        ]);

        $order->save();
        return response()->json(['message' => 'تم تحديث حالة الطلب بنجاح.'], 200);
    }
    public function customer_orders(Request $request )
    {
        $customer = Auth::user();
        $orders = Order::with('zoom_meeting', 'chatroom')->where('customer_id', $customer->id)->latest()->get();
        return response()->json(['data' => $orders],200);
    }
}
