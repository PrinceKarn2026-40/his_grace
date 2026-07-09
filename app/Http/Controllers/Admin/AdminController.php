<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // ─── Dashboard ───────────────────────────────────────────────
    public function dashboard()
    {
        $stats = [
            'total_revenue'  => Order::where('status', 'paid')->sum('total'),
            'total_orders'   => Order::count(),
            'total_products' => Product::count(),
            'total_users'    => User::where('is_admin', false)->count(),
        ];

        $revenueChart = Order::where('status', 'paid')
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(total) as revenue')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->get();

        $topProducts = Product::withSum('orderItems as sold', 'quantity')
            ->orderByDesc('sold')->take(5)->get();

        $recentOrders = Order::with('user')->latest()->take(8)->get();

        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')->groupBy('status')->pluck('count', 'status');

        return view('admin.dashboard', compact('stats', 'revenueChart', 'topProducts', 'recentOrders', 'ordersByStatus'));
    }

    // ─── Products ────────────────────────────────────────────────
    public function products(Request $request)
    {
        $query = Product::with('category');
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->upcoming === '1') {
            $query->upcoming();
        } elseif ($request->upcoming === '0') {
            $query->available();
        }
        $products   = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::all();
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function createProduct()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'sale_price'  => 'nullable|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'gender'      => 'nullable|in:men,women,unisex',
            'image'       => 'nullable|image|max:2048',
            'release_date' => 'nullable|date',
        ]);

        $data['slug']     = Str::slug($data['name']) . '-' . uniqid();
        $data['featured'] = $request->boolean('featured');
        $data['is_new']   = $request->boolean('is_new');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);
        return redirect()->route('admin.products')->with('success', 'Product created successfully!');
    }

    public function editProduct(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'sale_price'  => 'nullable|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'gender'      => 'nullable|in:men,women,unisex',
            'image'       => 'nullable|image|max:2048',
            'release_date' => 'nullable|date',
        ]);

        $data['featured'] = $request->boolean('featured');
        $data['is_new']   = $request->boolean('is_new');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        return redirect()->route('admin.products')->with('success', 'Product updated successfully!');
    }

    public function destroyProduct(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product deleted.');
    }

    // ─── Categories ──────────────────────────────────────────────
    public function categories()
    {
        $categories = Category::withCount('products')->latest()->get();
        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $data         = $request->validate(['name' => 'required|string|max:100|unique:categories,name']);
        $data['slug'] = Str::slug($data['name']);
        Category::create($data);
        return back()->with('success', 'Category created!');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $data         = $request->validate(['name' => 'required|string|max:100|unique:categories,name,' . $category->id]);
        $data['slug'] = Str::slug($data['name']);
        $category->update($data);
        return back()->with('success', 'Category updated!');
    }

    public function destroyCategory(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }

    // ─── Orders ──────────────────────────────────────────────────
    public function orders(Request $request)
    {
        $query = Order::with('user');
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->search) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
                  ->orWhere('id', $request->search);
        }
        $orders = $query->latest()->paginate(15)->withQueryString();
        return view('admin.orders', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $order->load('items.product', 'user');
        return view('admin.order-detail', compact('order'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:pending,paid,shipped,delivered,cancelled']);
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Order status updated.');
    }

    // ─── Customers ───────────────────────────────────────────────
    public function customers(Request $request)
    {
        $query = User::where('is_admin', false)->withCount('orders');
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        $customers = $query->latest()->paginate(15)->withQueryString();
        return view('admin.customers', compact('customers'));
    }

    public function showCustomer(User $user)
    {
        $user->load('orders.items.product');
        return view('admin.customer-detail', compact('user'));
    }

    public function toggleCustomerStatus(User $user)
    {
        // We use email_verified_at as a soft ban toggle
        if ($user->email_verified_at) {
            $user->update(['email_verified_at' => null]);
            $msg = 'Customer suspended.';
        } else {
            $user->update(['email_verified_at' => now()]);
            $msg = 'Customer activated.';
        }
        return back()->with('success', $msg);
    }

    // ─── Payments ─────────────────────────────────────────────────
    public function payments(Request $request)
    {
        $query = Payment::with(['order', 'user'])->latest();
        if ($request->status) {
            $query->where('status', $request->status);
        }
        $payments = $query->paginate(20)->withQueryString();
        $counts = [
            'pending'   => Payment::where('status', 'pending')->count(),
            'confirmed' => Payment::where('status', 'confirmed')->count(),
            'rejected'  => Payment::where('status', 'rejected')->count(),
        ];
        return view('admin.payments', compact('payments', 'counts'));
    }

    public function updatePaymentStatus(Request $request, Payment $payment)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,rejected']);
        $payment->update(['status' => $request->status]);

        // If confirmed, mark the order as paid
        if ($request->status === 'confirmed') {
            $payment->order->update(['status' => 'paid']);
        }
        // If rejected, revert order to pending
        if ($request->status === 'rejected') {
            $payment->order->update(['status' => 'pending']);
        }

        return back()->with('success', 'Payment status updated.');
    }

    // ─── Reports ─────────────────────────────────────────────────
    public function reports()
    {
        // Monthly revenue last 12 months
        $monthlyRevenue = Order::where('status', 'paid')
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(total) as revenue, COUNT(*) as orders')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->get();

        // Orders by status
        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')->groupBy('status')->pluck('count', 'status');

        // Top products
        $topProducts = Product::withSum('orderItems as sold', 'quantity')
            ->withSum('orderItems as revenue', 'price')
            ->orderByDesc('sold')->take(10)->get();

        // Top categories
        $topCategories = Category::withCount('products')
            ->withSum(['products as total_sold' => function ($q) {
                $q->join('order_items', 'products.id', '=', 'order_items.product_id');
            }], 'order_items.quantity')
            ->orderByDesc('total_sold')->get();

        // Summary
        $summary = [
            'revenue_today'   => Order::where('status', 'paid')->whereDate('created_at', today())->sum('total'),
            'revenue_month'   => Order::where('status', 'paid')->whereMonth('created_at', now()->month)->sum('total'),
            'revenue_total'   => Order::where('status', 'paid')->sum('total'),
            'orders_today'    => Order::whereDate('created_at', today())->count(),
            'avg_order_value' => Order::where('status', 'paid')->avg('total') ?? 0,
            'new_customers'   => User::where('is_admin', false)->whereMonth('created_at', now()->month)->count(),
        ];

        return view('admin.reports', compact('monthlyRevenue', 'ordersByStatus', 'topProducts', 'topCategories', 'summary'));
    }

    // ─── Settings ────────────────────────────────────────────────
    public function settings()
    {
        return view('admin.settings');
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|unique:users,email,' . auth()->id(),
            'current_password'      => 'nullable|string',
            'password'              => 'nullable|string|min:8|confirmed',
        ]);

        $user = auth()->user();
        $user->update(['name' => $request->name, 'email' => $request->email]);

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'Settings updated successfully!');
    }
}
