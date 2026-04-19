<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        return view('products.show', compact('product'));
    }

    public function checkout($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        return view('products.checkout', compact('product'));
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'customer_name' => 'required|string|max:255',
        ]);

        if ($request->has('is_cart_order')) {
            $cart = session()->get('cart', []);
            foreach($cart as $id => $details) {
                Order::create([
                    'product_id' => $id,
                    'phone' => $request->phone,
                    'customer_name' => $request->customer_name,
                    'address' => $request->address,
                    'notes' => $request->notes,
                    'status' => 'new'
                ]);
            }
            session()->forget('cart');
        } else {
            Order::create([
                'product_id' => $request->product_id,
                'phone' => $request->phone,
                'customer_name' => $request->customer_name,
                'address' => $request->address,
                'notes' => $request->notes,
                'status' => 'new'
            ]);
        }

        return redirect()->route('home')->with('success', 'Đơn hàng của bạn đã được tiếp nhận. Chúng tôi sẽ liên hệ xác nhận sớm nhất!');
    }
    public function search(Request $request)
    {
        $query = $request->input('query');
        $categoryId = $request->input('category_id');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $height = $request->input('height');
        $weight = $request->input('weight');
        $gender = $request->input('gender');

        $products = Product::query();

        if ($query) {
            $products->where('name', 'LIKE', "%{$query}%");
        }

        if ($categoryId && $categoryId !== 'all') {
            $products->where('category_id', $categoryId);
        }

        if ($height) {
            $products->where('min_height', '<=', $height)
                     ->where('max_height', '>=', $height);
        }

        if ($weight) {
            $products->where('min_weight', '<=', $weight)
                     ->where('max_weight', '>=', $weight);
        }

        if ($gender) {
            $products->where('gender', $gender);
        }

        if ($minPrice) {
            $products->where('price', '>=', $minPrice);
        }

        if ($maxPrice) {
            $products->where('price', '<=', $maxPrice);
        }

        if ($sort === 'low-high') {
            $products->orderBy('price', 'asc');
        } elseif ($sort === 'high-low') {
            $products->orderBy('price', 'desc');
        } else {
            $products->orderBy('created_at', 'desc');
        }

        $results = $products->take(10)->get();

        return response()->json($results);
    }
}
