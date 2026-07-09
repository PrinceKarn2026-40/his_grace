<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getKey(): array
    {
        return auth()->check()
            ? ['user_id' => auth()->id()]
            : ['session_id' => session()->getId()];
    }

    public function index()
    {
        $items = Cart::with('product.category')->where($this->getKey())->get();
        $total = $items->sum(fn($i) => $i->product->effective_price * $i->quantity);
        $view  = auth()->check() ? 'customer.cart' : 'shop.cart';
        return view($view, compact('items', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to add items to cart.');
        }
        $key = $this->getKey();
        $cart = Cart::where($key)->where('product_id', $product->id)->first();

        if ($cart) {
            $cart->increment('quantity');
        } else {
            Cart::create(array_merge($key, [
                'product_id' => $product->id,
                'quantity' => 1,
            ]));
        }

        return back()->with('success', "{$product->name} added to cart!");
    }

    public function update(Request $request, Cart $cart)
    {
        $cart->update(['quantity' => max(1, $request->quantity)]);
        return back();
    }

    public function remove(Cart $cart)
    {
        $cart->delete();
        return back()->with('success', 'Item removed from cart.');
    }

    public function count()
    {
        return Cart::where($this->getKey())->sum('quantity');
    }
}
