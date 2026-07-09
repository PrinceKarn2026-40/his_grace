<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $items = Cart::with('product')->where('user_id', auth()->id())->get();
        if ($items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }
        $total = $items->sum(fn($i) => $i->product->effective_price * $i->quantity);
        return view('customer.checkout', compact('items', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_name'    => 'required|string|max:100',
            'shipping_email'   => 'required|email',
            'shipping_phone'   => 'nullable|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_city'    => 'required|string',
            'payment_method'   => 'required|in:card,mobile_money,paypal',
        ]);

        $items = Cart::with('product')->where('user_id', auth()->id())->get();
        if ($items->isEmpty()) {
            return redirect()->route('cart');
        }

        $total = $items->sum(fn($i) => $i->product->effective_price * $i->quantity);
        $grandTotal = $total + ($total >= 500 ? 0 : 20);
        $isMomo = $request->payment_method === 'mobile_money';

        DB::transaction(function () use ($request, $items, $grandTotal, $isMomo) {
            $order = Order::create([
                'user_id'           => auth()->id(),
                'total'             => $grandTotal,
                'status'            => $isMomo ? 'pending' : 'paid',
                'payment_method'    => $request->payment_method,
                'payment_reference' => 'REF-' . strtoupper(uniqid()),
                'shipping_name'     => $request->shipping_name,
                'shipping_email'    => $request->shipping_email,
                'shipping_phone'    => $request->shipping_phone,
                'shipping_address'  => $request->shipping_address,
                'shipping_city'     => $request->shipping_city,
                'shipping_country'  => $request->shipping_country ?? 'Rwanda',
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->product->effective_price,
                ]);
                $item->product->decrement('stock', $item->quantity);
            }

            if ($isMomo) {
                Payment::create([
                    'order_id' => $order->id,
                    'user_id'  => auth()->id(),
                    'method'   => 'mobile_money',
                    'amount'   => $grandTotal,
                    'status'   => 'pending',
                ]);
            }

            Cart::where('user_id', auth()->id())->delete();
            auth()->user()->notify(new \App\Notifications\OrderPlaced($order));
            session(['last_order_id' => $order->id]);
        });

        if ($isMomo) {
            return redirect()->route('checkout.momo.instructions', session('last_order_id'));
        }

        return redirect()->route('orders.show', session('last_order_id'))
            ->with('success', 'Order placed successfully! Payment confirmed.');
    }

    public function momoInstructions(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);
        return view('customer.momo-instructions', compact('order'));
    }

    public function momoConfirm(Request $request, Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);

        $request->validate([
            'sender_number'  => 'required|string|max:20',
            'transaction_id' => 'required|string|max:100',
        ]);

        $payment = $order->payments()->where('method', 'mobile_money')->latest()->first();

        if ($payment) {
            $payment->update([
                'sender_number'  => $request->sender_number,
                'transaction_id' => $request->transaction_id,
                'status'         => 'pending', // admin will confirm
            ]);
        }

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Payment details submitted! We will confirm your payment shortly.');
    }
}
