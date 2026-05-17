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
            'payment_method' => 'required|in:cod,vnpay',
        ]);

        $orderIds = [];
        $totalAmount = $request->total_amount ?? 0;

        if ($request->has('is_cart_order')) {
            $cart = session()->get('cart', []);
            foreach($cart as $id => $details) {
                $order = Order::create([
                    'product_id' => $id,
                    'phone' => $request->phone,
                    'customer_name' => $request->customer_name,
                    'address' => $request->address,
                    'notes' => $request->notes,
                    'status' => 'new',
                    'payment_method' => $request->payment_method,
                    'payment_status' => 'pending',
                    'total_amount' => $details['price'] * $details['quantity']
                ]);
                $orderIds[] = $order->id;
            }
            if ($request->payment_method === 'cod') {
                session()->forget('cart');
            }
        } else {
            $order = Order::create([
                'product_id' => $request->product_id,
                'phone' => $request->phone,
                'customer_name' => $request->customer_name,
                'address' => $request->address,
                'notes' => $request->notes,
                'status' => 'new',
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'total_amount' => $totalAmount
            ]);
            $orderIds[] = $order->id;
        }

        if ($request->payment_method === 'vnpay') {
            return $this->createVNPPayment($orderIds, $totalAmount);
        }

        return redirect()->route('home')->with('success', 'Đơn hàng của bạn đã được tiếp nhận. Chúng tôi sẽ liên hệ xác nhận sớm nhất!');
    }

    private function createVNPPayment($orderIds, $totalAmount)
    {
        $vnp_Url = config('vnpay.vnp_Url');
        $vnp_Returnurl = config('vnpay.vnp_Returnurl');
        $vnp_TmnCode = config('vnpay.vnp_TmnCode');
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');

        $vnp_TxnRef = implode('_', $orderIds) . '_' . time();
        $vnp_OrderInfo = "Thanh toan don hang: " . $vnp_TxnRef;
        $vnp_OrderType = "billpayment";
        $vnp_Amount = $totalAmount * 100;
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $query .= '&' . urlencode($key) . "=" . urlencode($value);
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $query .= urlencode($key) . "=" . urlencode($value);
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= '&vnp_SecureHash=' . $vnpSecureHash;
        }

        return redirect()->away($vnp_Url);
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
        $sort = $request->input('sort');

        $products = Product::with('category');

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
