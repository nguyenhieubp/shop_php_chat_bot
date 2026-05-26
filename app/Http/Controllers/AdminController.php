<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Models\Post;
use App\Models\Feedback;
use App\Models\Slider;
use App\Models\BotSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function loginForm()
    {
        if (Session::has('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $password = env('ADMIN_PASSWORD', 'admin123');
        if ($request->password === $password) {
            Session::put('admin_logged_in', true);
            return redirect()->route('admin.dashboard');
        }
        return back()->with('error', 'Mật khẩu không đúng!');
    }

    public function logout()
    {
        Session::forget('admin_logged_in');
        return redirect()->route('admin.login');
    }

    public function index()
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        
        $orderCount = Order::count();
        $productCount = Product::count();
        $customerCount = Order::distinct('phone')->count('phone');
        $recentOrders = Order::with('product')->latest()->take(10)->get();
        
        return view('admin.dashboard', compact('orderCount', 'productCount', 'customerCount', 'recentOrders'));
    }

    public function products()
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        $products = Product::with('category')->latest()->get();
        $categories = Category::all();
        return view('admin.products', compact('products', 'categories'));
    }

    public function orders(Request $request)
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        
        $query = Order::with('product');
        
        if ($request->filled('search_date')) {
            $query->whereDate('created_at', $request->search_date);
        }
        
        if ($request->filled('search_customer')) {
            $search = $request->search_customer;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('search_product')) {
            $search = $request->search_product;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('filter_status')) {
            $query->where('status', $request->filter_status);
        }
        
        $orders = $query->latest()->paginate(10)->withQueryString();
        return view('admin.orders', compact('orders'));
    }

    public function createProductForm()
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        $categories = Category::all();
        return view('admin.products_create', compact('categories'));
    }

    public function createProduct(Request $request)
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        
        $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destPath = public_path('uploads/products');

            try {
                if (!\Illuminate\Support\Facades\File::exists($destPath)) {
                    \Illuminate\Support\Facades\File::makeDirectory($destPath, 0777, true, true);
                }
                copy($image->getRealPath(), $destPath . DIRECTORY_SEPARATOR . $imageName);
            } catch (\Exception $e) {
                return back()->with('error', 'Lỗi lưu file: ' . $e->getMessage() . '. Nguyên nhân: Thư mục bị OneDrive khóa hoặc không có quyền ghi.');
            }
            $imagePath = 'uploads/products/' . $imageName;
        }

        Product::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name) . '-' . time(),
            'category_id' => $request->category_id,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $imagePath,
            'is_featured' => $request->has('is_featured'),
            'is_active' => $request->has('is_active'),
            'stock' => $request->stock ?? 0,
            'min_height' => $request->min_height,
            'max_height' => $request->max_height,
            'min_weight' => $request->min_weight,
            'max_weight' => $request->max_weight,
            'gender' => $request->gender,
            'material' => $request->material,
        ]);

        return redirect()->route('admin.products')->with('success', 'Sản phẩm đã được thêm!');
    }

    public function editProductForm($id)
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products_edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, $id)
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && \Illuminate\Support\Facades\File::exists(public_path($product->image))) {
                \Illuminate\Support\Facades\File::delete(public_path($product->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destPath = public_path('uploads/products');

            try {
                if (!\Illuminate\Support\Facades\File::exists($destPath)) {
                    \Illuminate\Support\Facades\File::makeDirectory($destPath, 0777, true, true);
                }
                copy($image->getRealPath(), $destPath . DIRECTORY_SEPARATOR . $imageName);
            } catch (\Exception $e) {
                return back()->with('error', 'Lỗi lưu file: ' . $e->getMessage());
            }
            $product->image = 'uploads/products/' . $imageName;
        }

        $product->update([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name) . '-' . time(),
            'category_id' => $request->category_id,
            'price' => $request->price,
            'description' => $request->description,
            'is_featured' => $request->has('is_featured'),
            'is_active' => $request->has('is_active'),
            'stock' => $request->stock ?? 0,
            'min_height' => $request->min_height,
            'max_height' => $request->max_height,
            'min_weight' => $request->min_weight,
            'max_weight' => $request->max_weight,
            'gender' => $request->gender,
            'material' => $request->material,
        ]);

        return redirect()->route('admin.products')->with('success', 'Sản phẩm đã được cập nhật!');
    }

    public function deleteProduct($id)
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        $product = Product::findOrFail($id);
        
        // Delete image file if exists
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }
        
        $product->delete();
        return back()->with('success', 'Sản phẩm đã được xóa thành công!');
    }

    public function toggleProductActive($id)
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        $product = Product::findOrFail($id);
        $product->update(['is_active' => !$product->is_active]);
        return back()->with('success', 'Trạng thái hiển thị sản phẩm đã thay đổi!');
    }

    public function categories()
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        $categories = Category::withCount('products')->latest()->paginate(10);
        return view('admin.categories', compact('categories'));
    }

    public function createCategoryForm()
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        return view('admin.categories_create');
    }

    public function storeCategory(Request $request)
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        $request->validate(['name' => 'required|unique:categories,name']);
        Category::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name)
        ]);
        return redirect()->route('admin.categories')->with('success', 'Danh mục đã được thêm!');
    }

    public function deleteCategory($id)
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        $category = Category::findOrFail($id);
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Không thể xóa danh mục đang có sản phẩm!');
        }
        $category->delete();
        return back()->with('success', 'Danh mục đã được xóa!');
    }

    public function posts()
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        $posts = Post::latest()->get();
        return view('admin.posts', compact('posts'));
    }

    public function createPostForm()
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        return view('admin.posts_create');
    }

    public function storePost(Request $request)
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destPath = public_path('uploads/posts');

            try {
                if (!\Illuminate\Support\Facades\File::exists($destPath)) {
                    \Illuminate\Support\Facades\File::makeDirectory($destPath, 0777, true, true);
                }
                copy($image->getRealPath(), $destPath . DIRECTORY_SEPARATOR . $imageName);
            } catch (\Exception $e) {
                return back()->with('error', 'Lỗi lưu file: ' . $e->getMessage());
            }
            $imagePath = 'uploads/posts/' . $imageName;
        }

        Post::create([
            'title' => $request->title,
            'slug' => \Illuminate\Support\Str::slug($request->title) . '-' . time(),
            'content' => $request->content,
            'image' => $imagePath,
            'is_published' => true,
        ]);

        return redirect()->route('admin.posts')->with('success', 'Bài viết đã được đăng!');
    }

    public function deletePost($id)
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        $post = Post::findOrFail($id);
        
        // Delete image file if exists
        if ($post->image && file_exists(public_path($post->image))) {
            unlink(public_path($post->image));
        }
        
        $post->delete();
        return back()->with('success', 'Bài viết đã được xóa!');
    }

    public function editPostForm($id)
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        $post = Post::findOrFail($id);
        return view('admin.posts_edit', compact('post'));
    }

    public function updatePost(Request $request, $id)
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        $post = Post::findOrFail($id);
        
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($post->image && \Illuminate\Support\Facades\File::exists(public_path($post->image))) {
                \Illuminate\Support\Facades\File::delete(public_path($post->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destPath = public_path('uploads/posts');

            try {
                if (!\Illuminate\Support\Facades\File::exists($destPath)) {
                    \Illuminate\Support\Facades\File::makeDirectory($destPath, 0777, true, true);
                }
                copy($image->getRealPath(), $destPath . DIRECTORY_SEPARATOR . $imageName);
            } catch (\Exception $e) {
                return back()->with('error', 'Lỗi lưu file: ' . $e->getMessage());
            }
            $post->image = 'uploads/posts/' . $imageName;
        }

        $post->update([
            'title' => $request->title,
            'content' => $request->content,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('admin.posts')->with('success', 'Bài viết đã được cập nhật thành công!');
    }

    public function reports()
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        $feedbacks = Feedback::latest()->get();
        return view('admin.reports', compact('feedbacks'));
    }

    public function deleteFeedback($id)
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();
        return back()->with('success', 'Báo cáo/Phản hồi đã được xóa thành công!');
    }

    public function storeFeedback(Request $request)
    {
        $request->validate([
            'message' => 'required',
            'contact' => 'required'
        ]);

        Feedback::create([
            'name' => $request->name,
            'contact' => $request->contact,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Cảm ơn bạn! Báo cáo/Phản hồi của bạn đã được gửi tới ban quản trị.');
    }

    public function updateOrder($id, Request $request)
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        $order = Order::findOrFail($id);
        
        $data = [];
        if ($request->has('status')) $data['status'] = $request->status;
        
        if ($request->has('payment_status')) {
            if ($order->status === 'completed') {
                $data['payment_status'] = $request->payment_status;
            } else {
                return back()->with('error', 'Chỉ có thể thay đổi trạng thái thanh toán khi đơn hàng đã ở trạng thái Hoàn thành!');
            }
        }
        
        $order->update($data);
        return back()->with('success', 'Đơn hàng đã được cập nhật!');
    }

    public function deleteOrder($id)
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        $order = Order::findOrFail($id);
        $order->delete();
        return back()->with('success', 'Đơn hàng đã được đưa vào Thùng rác!');
    }

    public function trashOrders()
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        $orders = Order::onlyTrashed()->with('product')->latest()->get();
        return view('admin.orders_trash', compact('orders'));
    }

    public function restoreOrder($id)
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->restore();
        return back()->with('success', 'Đơn hàng đã được khôi phục thành công!');
    }

    public function forceDeleteOrder($id)
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->forceDelete();
        return back()->with('success', 'Đơn hàng đã được xóa vĩnh viễn khỏi hệ thống!');
    }

    // Sliders
    public function sliders()
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        $sliders = Slider::orderBy('position', 'desc')->get();
        return view('admin.sliders', compact('sliders'));
    }

    public function createSliderForm()
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        return view('admin.sliders_create');
    }

    public function storeSlider(Request $request)
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'position' => 'nullable|integer',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destPath = public_path('uploads/sliders');

            try {
                if (!\Illuminate\Support\Facades\File::exists($destPath)) {
                    \Illuminate\Support\Facades\File::makeDirectory($destPath, 0777, true, true);
                }
                copy($image->getRealPath(), $destPath . DIRECTORY_SEPARATOR . $imageName);
            } catch (\Exception $e) {
                return back()->with('error', 'Lỗi lưu file: ' . $e->getMessage());
            }
            $imagePath = 'uploads/sliders/' . $imageName;
        }

        Slider::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image' => $imagePath,
            'link' => $request->link,
            'position' => $request->position ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.sliders')->with('success', 'Slider đã được thêm!');
    }

    public function editSliderForm($id)
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        $slider = Slider::findOrFail($id);
        return view('admin.sliders_edit', compact('slider'));
    }

    public function updateSlider(Request $request, $id)
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        $slider = Slider::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'position' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            if ($slider->image && \Illuminate\Support\Facades\File::exists(public_path($slider->image))) {
                \Illuminate\Support\Facades\File::delete(public_path($slider->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destPath = public_path('uploads/sliders');

            try {
                if (!\Illuminate\Support\Facades\File::exists($destPath)) {
                    \Illuminate\Support\Facades\File::makeDirectory($destPath, 0777, true, true);
                }
                copy($image->getRealPath(), $destPath . DIRECTORY_SEPARATOR . $imageName);
            } catch (\Exception $e) {
                return back()->with('error', 'Lỗi lưu file: ' . $e->getMessage());
            }
            $slider->image = 'uploads/sliders/' . $imageName;
        }

        $slider->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'link' => $request->link,
            'position' => $request->position ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.sliders')->with('success', 'Slider đã được cập nhật!');
    }

    public function deleteSlider($id)
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        $slider = Slider::findOrFail($id);
        
        if ($slider->image && file_exists(public_path($slider->image))) {
            unlink(public_path($slider->image));
        }

        $slider->delete();
        return back()->with('success', 'Slider đã được xóa!');
    }

    public function botSettings()
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        
        $settings = BotSetting::all();
        // If empty, seed initial data
        if ($settings->isEmpty()) {
            $initialSettings = [
                ['key' => 'bot_welcome_msg', 'value' => 'Chào mừng bạn đến với Cosmetic Store! 🌸', 'label' => 'Lời chào khởi đầu', 'group' => 'general'],
                ['key' => 'bot_start_btn', 'value' => 'Bắt đầu', 'label' => 'Tên nút Bắt đầu', 'group' => 'general'],
                ['key' => 'bot_menu_msg', 'value' => 'Tôi là trợ lý ảo hỗ trợ bạn tìm kiếm và đặt hàng. Bạn muốn làm gì?', 'label' => 'Lời dẫn Menu chính', 'group' => 'menu'],
                ['key' => 'bot_shopping_btn', 'value' => '🚀 Bắt đầu mua sắm', 'label' => 'Nút Mua sắm nhanh', 'group' => 'menu'],
                ['key' => 'bot_search_btn', 'value' => '🔍 Tìm sản phẩm', 'label' => 'Nút Tìm sản phẩm', 'group' => 'menu'],
                ['key' => 'bot_track_btn', 'value' => '📦 Tra cứu đơn hàng', 'label' => 'Nút Tra cứu đơn hàng', 'group' => 'menu'],
                ['key' => 'bot_blog_btn', 'value' => '📝 Đọc Blog', 'label' => 'Nút Đọc Blog', 'group' => 'menu'],
                ['key' => 'bot_feedback_btn', 'value' => '📧 Gửi góp ý', 'label' => 'Nút Gửi góp ý', 'group' => 'menu'],
                ['key' => 'bot_contact_btn', 'value' => '📞 Liên hệ', 'label' => 'Nút Liên hệ', 'group' => 'menu'],
                ['key' => 'bot_placeholder', 'value' => 'Nhấn chọn hoặc nhập tin nhắn...', 'label' => 'Dòng gợi ý (Placeholder)', 'group' => 'general'],
            ];
            foreach ($initialSettings as $s) {
                BotSetting::create($s);
            }
            $settings = BotSetting::all();
        }
        
        return view('admin.bot_settings', compact('settings'));
    }

    public function updateBotSettings(Request $request)
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        
        if ($request->has('settings')) {
            foreach ($request->settings as $key => $value) {
                BotSetting::where('key', $key)->update(['value' => $value]);
            }
        }
        
        return back()->with('success', 'Cấu hình Chatbot đã được cập nhật thành công!');
    }

    public function orderStats(Request $request)
    {
        if (!Session::has('admin_logged_in')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $type = $request->input('type', 'day'); // date, day, month, year

        $labels = [];
        $revenueData = [];
        $orderCountData = [];
        $tableData = [];

        if ($type === 'date') {
            $dateVal = $request->input('date', now()->format('Y-m-d')); // YYYY-MM-DD
            try {
                $date = \Carbon\Carbon::createFromFormat('Y-m-d', $dateVal);
            } catch (\Exception $e) {
                $date = now();
                $dateVal = $date->format('Y-m-d');
            }

            $startDate = $date->copy()->startOfDay();
            $endDate = $date->copy()->endOfDay();

            $orders = Order::whereBetween('created_at', [$startDate, $endDate])->get();
            $completedCount = [];

            // Initialize hourly structure (00:00 to 23:00)
            for ($h = 0; $h < 24; $h++) {
                $labels[] = sprintf('%02d:00', $h);
                $revenueData[$h] = 0;
                $orderCountData[$h] = 0;
                $completedCount[$h] = 0;
            }

            foreach ($orders as $order) {
                $hour = (int)$order->created_at->format('H');
                $orderCountData[$hour]++;
                if ($order->payment_status === 'paid') {
                    $revenueData[$hour] += (float)$order->total_amount;
                    $completedCount[$hour]++;
                }
            }

            $revArr = [];
            $countArr = [];
            for ($h = 0; $h < 24; $h++) {
                $revArr[] = $revenueData[$h];
                $countArr[] = $orderCountData[$h];
                
                $tableData[] = [
                    'time' => sprintf('%02d:00 - %02d:59', $h, $h),
                    'revenue' => $revenueData[$h],
                    'orders' => $orderCountData[$h],
                    'completed' => $completedCount[$h]
                ];
            }

            $revenueData = $revArr;
            $orderCountData = $countArr;

        } elseif ($type === 'day') {
            $yearMonth = $request->input('month', now()->format('Y-m')); // YYYY-MM
            try {
                $date = \Carbon\Carbon::createFromFormat('Y-m', $yearMonth);
            } catch (\Exception $e) {
                $date = now();
                $yearMonth = $date->format('Y-m');
            }

            $startDate = $date->copy()->startOfMonth();
            $endDate = $date->copy()->endOfMonth();

            $orders = Order::whereBetween('created_at', [$startDate, $endDate])->get();
            $daysInMonth = $date->daysInMonth;
            $completedCount = [];

            // Initialize daily structure
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $dayDate = $date->copy()->day($d);
                $dateStr = $dayDate->format('Y-m-d');
                $labels[] = $dayDate->format('d/m');
                
                $revenueData[$dateStr] = 0;
                $orderCountData[$dateStr] = 0;
                $completedCount[$dateStr] = 0;
            }

            foreach ($orders as $order) {
                $dayStr = $order->created_at->format('Y-m-d');
                if (isset($revenueData[$dayStr])) {
                    $orderCountData[$dayStr]++;
                    if ($order->payment_status === 'paid') {
                        $revenueData[$dayStr] += (float)$order->total_amount;
                        $completedCount[$dayStr]++;
                    }
                }
            }

            // Convert to indexed arrays and build table data
            $revArr = [];
            $countArr = [];
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $dayDate = $date->copy()->day($d);
                $dateStr = $dayDate->format('Y-m-d');
                $revArr[] = $revenueData[$dateStr];
                $countArr[] = $orderCountData[$dateStr];
                
                $tableData[] = [
                    'time' => $dayDate->format('d/m/Y'),
                    'revenue' => $revenueData[$dateStr],
                    'orders' => $orderCountData[$dateStr],
                    'completed' => $completedCount[$dateStr]
                ];
            }
            
            $revenueData = $revArr;
            $orderCountData = $countArr;

        } elseif ($type === 'month') {
            $year = $request->input('year', now()->format('Y')); // YYYY
            if (!is_numeric($year) || strlen($year) !== 4) {
                $year = now()->format('Y');
            }

            $startDate = \Carbon\Carbon::create($year, 1, 1)->startOfYear();
            $endDate = \Carbon\Carbon::create($year, 12, 31)->endOfYear();

            $orders = Order::whereBetween('created_at', [$startDate, $endDate])->get();
            $completedCount = [];

            // Initialize monthly structure
            for ($m = 1; $m <= 12; $m++) {
                $monthStr = sprintf('%s-%02d', $year, $m);
                $labels[] = 'Thg ' . $m;
                
                $revenueData[$monthStr] = 0;
                $orderCountData[$monthStr] = 0;
                $completedCount[$monthStr] = 0;
            }

            foreach ($orders as $order) {
                $monthStr = $order->created_at->format('Y-m');
                if (isset($revenueData[$monthStr])) {
                    $orderCountData[$monthStr]++;
                    if ($order->payment_status === 'paid') {
                        $revenueData[$monthStr] += (float)$order->total_amount;
                        $completedCount[$monthStr]++;
                    }
                }
            }

            $revArr = [];
            $countArr = [];
            for ($m = 1; $m <= 12; $m++) {
                $monthStr = sprintf('%s-%02d', $year, $m);
                $revArr[] = $revenueData[$monthStr];
                $countArr[] = $orderCountData[$monthStr];
                
                $tableData[] = [
                    'time' => 'Tháng ' . $m . '/' . $year,
                    'revenue' => $revenueData[$monthStr],
                    'orders' => $orderCountData[$monthStr],
                    'completed' => $completedCount[$monthStr]
                ];
            }

            $revenueData = $revArr;
            $orderCountData = $countArr;

        } else { // type === 'year'
            $currentYear = (int)now()->format('Y');
            $startYear = $currentYear - 4; // Past 5 years
            $startDate = \Carbon\Carbon::create($startYear, 1, 1)->startOfYear();
            $endDate = now()->endOfYear();

            $orders = Order::whereBetween('created_at', [$startDate, $endDate])->get();
            $completedCount = [];

            // Initialize yearly structure
            for ($y = $startYear; $y <= $currentYear; $y++) {
                $yearStr = (string)$y;
                $labels[] = 'Năm ' . $y;
                
                $revenueData[$yearStr] = 0;
                $orderCountData[$yearStr] = 0;
                $completedCount[$yearStr] = 0;
            }

            foreach ($orders as $order) {
                $yearStr = $order->created_at->format('Y');
                if (isset($revenueData[$yearStr])) {
                    $orderCountData[$yearStr]++;
                    if ($order->payment_status === 'paid') {
                        $revenueData[$yearStr] += (float)$order->total_amount;
                        $completedCount[$yearStr]++;
                    }
                }
            }

            $revArr = [];
            $countArr = [];
            for ($y = $startYear; $y <= $currentYear; $y++) {
                $yearStr = (string)$y;
                $revArr[] = $revenueData[$yearStr];
                $countArr[] = $orderCountData[$yearStr];
                
                $tableData[] = [
                    'time' => 'Năm ' . $y,
                    'revenue' => $revenueData[$yearStr],
                    'orders' => $orderCountData[$yearStr],
                    'completed' => $completedCount[$yearStr]
                ];
            }

            $revenueData = $revArr;
            $orderCountData = $countArr;
        }

        // Aggregate summary values
        $totalRevenue = array_sum($revenueData);
        $totalOrders = array_sum($orderCountData);
        
        $completedOrders = 0;
        foreach ($tableData as $row) {
            $completedOrders += $row['completed'];
        }
        $pendingOrders = $totalOrders - $completedOrders;
        $successRate = $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 1) : 0;

        return response()->json([
            'labels' => $labels,
            'revenue' => $revenueData,
            'orders' => $orderCountData,
            'summary' => [
                'total_revenue' => $totalRevenue,
                'total_orders' => $totalOrders,
                'completed_orders' => $completedOrders,
                'pending_orders' => $pendingOrders,
                'success_rate' => $successRate
            ],
            'table_data' => $tableData
        ]);
    }

    public function revenueReport(Request $request)
    {
        if (!Session::has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        // Default filters
        $startDateVal = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDateVal = $request->input('end_date', now()->format('Y-m-d'));
        $paymentStatus = $request->input('payment_status', 'all');
        $orderStatus = $request->input('status', 'all');

        $query = Order::query()->with('product');

        // Apply date range filter
        if ($startDateVal) {
            $query->whereDate('created_at', '>=', $startDateVal);
        }
        if ($endDateVal) {
            $query->whereDate('created_at', '<=', $endDateVal);
        }

        // Apply payment status filter
        if ($paymentStatus !== 'all') {
            $query->where('payment_status', $paymentStatus);
        }

        // Apply order status filter
        if ($orderStatus !== 'all') {
            $query->where('status', $orderStatus);
        }

        $orders = $query->latest()->get();

        // Calculate KPI Metrics
        $totalOrders = $orders->count();
        $completedOrders = $orders->where('status', 'completed')->count();
        $pendingOrders = $orders->whereIn('status', ['new', 'pending'])->count();
        
        // Revenue is calculated only for orders with payment_status === 'paid' or status === 'completed'
        $revenueOrders = $orders->filter(function($o) {
            return $o->payment_status === 'paid';
        });
        $totalRevenue = $revenueOrders->sum('total_amount');
        
        // Average Order Value (AOV) based on all orders
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return view('admin.revenue_report', compact(
            'orders',
            'startDateVal',
            'endDateVal',
            'paymentStatus',
            'orderStatus',
            'totalRevenue',
            'totalOrders',
            'completedOrders',
            'pendingOrders',
            'averageOrderValue'
        ));
    }

    public function exportRevenue(Request $request)
    {
        if (!Session::has('admin_logged_in')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Include SimpleXLSXGen helper
        include_once app_path('Helpers/SimpleXLSXGen.php');

        // Filters
        $startDateVal = $request->input('start_date');
        $endDateVal = $request->input('end_date');
        $paymentStatus = $request->input('payment_status', 'all');
        $orderStatus = $request->input('status', 'all');

        $query = Order::query()->with('product');

        if ($startDateVal) {
            $query->whereDate('created_at', '>=', $startDateVal);
        }
        if ($endDateVal) {
            $query->whereDate('created_at', '<=', $endDateVal);
        }
        if ($paymentStatus !== 'all') {
            $query->where('payment_status', $paymentStatus);
        }
        if ($orderStatus !== 'all') {
            $query->where('status', $orderStatus);
        }

        $orders = $query->latest()->get();

        // Build Excel Array rows
        $data = [
            [
                '<b>Mã Đơn Hàng</b>',
                '<b>Ngày Đặt</b>',
                '<b>Khách Hàng</b>',
                '<b>Số Điện Thoại</b>',
                '<b>Sản Phẩm Đặt Mua</b>',
                '<b>Phương Thức Thanh Toán</b>',
                '<b>Trạng Thái Thanh Toán</b>',
                '<b>Trạng Thái Đơn Hàng</b>',
                '<b>Doanh Thu Tính (VND)</b>',
                '<b>Tổng Số Tiền Đơn (VND)</b>'
            ]
        ];

        foreach ($orders as $order) {
            $isRevenue = ($order->payment_status === 'paid');
            $revenueContribution = $isRevenue ? (float)$order->total_amount : 0;

            $payStatusText = 'Chờ thanh toán';
            if ($order->payment_status === 'paid') {
                $payStatusText = 'Đã thanh toán';
            } elseif ($order->payment_status === 'failed') {
                $payStatusText = 'Thất bại';
            }

            $orderStatusText = 'Mới nhận';
            if ($order->status === 'pending') {
                $orderStatusText = 'Đang xử lý';
            } elseif ($order->status === 'completed') {
                $orderStatusText = 'Hoàn tất';
            }

            $data[] = [
                (int)$order->id,
                $order->created_at->format('d/m/Y H:i:s'),
                $order->customer_name ?? 'Khách lẻ',
                $order->phone,
                $order->product->name ?? 'N/A',
                strtoupper($order->payment_method),
                $payStatusText,
                $orderStatusText,
                $revenueContribution,
                (float)$order->total_amount
            ];
        }

        // Add visual separation rows
        $data[] = [];
        $data[] = [];

        // Calculate summary metrics
        $uniqueCustomers = $orders->map(function($o) {
            return $o->phone ?: $o->customer_name;
        })->filter()->unique()->count();
        $totalOrdersCount = $orders->count();
        $totalRev = $orders->filter(function($o) {
            return $o->payment_status === 'paid';
        })->sum('total_amount');

        // Add footer report totals
        $data[] = ['<b>TỔNG CỘNG THỐNG KÊ</b>'];
        $data[] = ['<b>Tổng số khách hàng:</b>', $uniqueCustomers . ' khách hàng'];
        $data[] = ['<b>Tổng số đơn hàng:</b>', $totalOrdersCount . ' đơn hàng'];
        $data[] = ['<b>Tổng doanh thu thực nhận:</b>', number_format($totalRev) . ' VNĐ'];

        $filename = 'bao-cao-doanh-thu.xlsx';

        // Set columns auto-fit width and trigger direct xlsx streaming download
        \Shuchkin\SimpleXLSXGen::fromArray($data)->downloadAs($filename);
        exit;
    }

    public function customers(Request $request)
    {
        if (!Session::has('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $search = $request->input('search');

        $query = Order::select('phone', \DB::raw('MAX(customer_name) as name'), \DB::raw('COUNT(id) as total_orders'), \DB::raw('SUM(total_amount) as total_spent'))
            ->whereNotNull('phone')
            ->where('phone', '<>', '')
            ->groupBy('phone');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        $customers = $query->orderBy(\DB::raw('MAX(id)'), 'desc')->paginate(15)->withQueryString();

        return view('admin.customers', compact('customers', 'search'));
    }
}
