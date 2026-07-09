<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()->with('items.product')->latest()->paginate(10);
        return view('customer.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_if($order->user_id !== auth()->id() && !auth()->user()->is_admin, 403);
        $order->load('items.product');
        return view('customer.order-detail', compact('order'));
    }
}
