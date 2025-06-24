<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Order::query();
        if (!$user->is_admin) {
            $query->where('user_id', $user->id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        $orders = $query->with('orderItems')->get();
        return response()->json($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);
        $user = Auth::user();
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total' => 0,
        ]);
        $total = 0;
        foreach ($validated['items'] as $item) {
            $product = \App\Models\Product::findOrFail($item['product_id']);
            $subtotal = $product->price * $item['quantity'];
            $total += $subtotal;
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ]);
        }
        $order->update(['total' => $total]);
        return response()->json($order->load('orderItems'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $order = Order::with('orderItems')->findOrFail($id);
        if ($order->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return response()->json($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);
        // Authorization for admin is handled by middleware
        $user = Auth::user();
        if ($order->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $validated = $request->validate([
            // Only allow updating non-status fields here
        ]);
        $order->update($validated);
        return response()->json($order);
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Request $request, string $id)
    {
        $order = Order::findOrFail($id);
        // is_admin middleware already checked
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,shipped,completed,cancelled',
        ]);
        $order->update(['status' => $validated['status']]);
        return response()->json($order);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        // Authorization for admin is handled by middleware
        $user = Auth::user();
        if ($order->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $order->delete();
        return response()->json(null, 204);
    }
}
