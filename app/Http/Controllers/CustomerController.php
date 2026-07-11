<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $user   = auth()->user();
        $stats  = [
            'total_orders'    => $user->orders()->count(),
            'total_spent'     => $user->orders()->where('status', 'paid')->sum('total'),
            'pending_orders'  => $user->orders()->where('status', 'pending')->count(),
            'wishlist_count'  => $user->wishlists()->count(),
        ];
        $recentOrders = $user->orders()->with('items.product')->latest()->take(5)->get();
        return view('customer.dashboard', compact('stats', 'recentOrders'));
    }

    public function profile()
    {
        return view('customer.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'             => 'required|string|max:100',
            'email'            => 'required|email|unique:users,email,' . $user->id,
            'photo'            => 'nullable|image|max:2048|mimes:jpg,jpeg,png,webp',
            'current_password' => 'nullable|string',
            'password'         => 'nullable|string|min:8|confirmed',
        ]);

        $user->update(['name' => $request->name, 'email' => $request->email]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $result = (new \Cloudinary\Cloudinary(config('cloudinary.cloud_url')))
                ->uploadApi()->upload($file->getRealPath(), ['folder' => 'hisgrace/profiles']);
            $user->profile_photo_path = $result['secure_url'];
            $user->save();
        }

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'Profile updated successfully!');
    }

    public function trackOrder(Request $request)
    {
        $order = null;
        if ($request->order_id) {
            $order = Order::with('items.product')
                ->where('id', $request->order_id)
                ->where('user_id', auth()->id())
                ->first();

            if (!$order) {
                return back()->with('error', 'Order not found. Please check the order ID.');
            }
        }
        return view('customer.track-order', compact('order'));
    }
}
