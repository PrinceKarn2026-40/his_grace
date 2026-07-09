<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function toggle(Product $product)
    {
        $existing = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $product->id)->first();

        if ($existing) {
            $existing->delete();
            $msg = 'Removed from wishlist.';
        } else {
            Wishlist::create(['user_id' => auth()->id(), 'product_id' => $product->id]);
            $msg = 'Added to wishlist!';
        }

        return back()->with('success', $msg);
    }

    public function index()
    {
        $items = auth()->user()->wishlists()->with('product')->get();
        return view('shop.wishlist', compact('items'));
    }
}
