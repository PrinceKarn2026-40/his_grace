<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function home()
    {
        $heroSlider  = Product::with('category')->available()->latest()->take(6)->get();
        $featured    = Product::with('category')->available()->where('featured', true)->latest()->take(8)->get();
        $newArrivals = Product::with('category')->available()->where('is_new', true)->latest()->take(8)->get();
        $upcoming    = Product::with('category')->upcoming()->orderBy('release_date')->take(6)->get();
        $categories  = Category::withCount('products')->get();
        return view('shop.home', compact('heroSlider', 'featured', 'newArrivals', 'upcoming', 'categories'));
    }

    public function index(Request $request)
    {
        $query = Product::with('category')->available();

        if ($request->category) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }
        if ($request->gender) {
            $query->where('gender', $request->gender);
        }
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }
        if ($request->sort === 'price_asc') {
            $query->orderBy('price');
        } elseif ($request->sort === 'price_desc') {
            $query->orderByDesc('price');
        } elseif ($request->sort === 'newest') {
            $query->latest();
        } else {
            $query->orderByDesc('featured');
        }

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::all();
        return view('shop.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load('category', 'reviews.user');

        $recommended = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->available()
            ->inRandomOrder()
            ->take(4)
            ->get();

        $inWishlist = auth()->check()
            ? auth()->user()->wishlists()->where('product_id', $product->id)->exists()
            : false;

        return view('shop.show', compact('product', 'recommended', 'inWishlist'));
    }
}
