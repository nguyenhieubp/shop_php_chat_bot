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
        $recentOrders = Order::with('product')->latest()->take(10)->get();
        
        return view('admin.dashboard', compact('orderCount', 'productCount', 'recentOrders'));
    }

    public function products()
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        $products = Product::with('category')->latest()->get();
        $categories = Category::all();
        return view('admin.products', compact('products', 'categories'));
    }

    public function orders()
    {
        if (!Session::has('admin_logged_in')) return redirect()->route('admin.login');
        $orders = Order::with('product')->latest()->get();
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
            $image->move(public_path('uploads/products'), $imageName);
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
            // Delete old image
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/products'), $imageName);
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
        $categories = Category::withCount('products')->get();
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
            $image->move(public_path('uploads/posts'), $imageName);
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
            // Delete old image
            if ($post->image && file_exists(public_path($post->image))) {
                unlink(public_path($post->image));
            }
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/posts'), $imageName);
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
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Trạng thái đơn hàng đã được cập nhật!');
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
            $image->move(public_path('uploads/sliders'), $imageName);
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
            // Delete old image
            if ($slider->image && file_exists(public_path($slider->image))) {
                unlink(public_path($slider->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/sliders'), $imageName);
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
                ['key' => 'bot_welcome_msg', 'value' => 'Chào mừng bạn đến với Cosmetic Store! 🌸', 'label' => 'Lời chào khởi đầu'],
                ['key' => 'bot_start_btn', 'value' => 'Bắt đầu', 'label' => 'Tên nút Bắt đầu'],
                ['key' => 'bot_menu_msg', 'value' => 'Tôi là trợ lý ảo hỗ trợ bạn tìm kiếm và đặt hàng. Bạn muốn làm gì?', 'label' => 'Lời dẫn Menu chính'],
                ['key' => 'bot_shopping_btn', 'value' => '🚀 Bắt đầu mua sắm', 'label' => 'Nút Mua sắm nhanh'],
                ['key' => 'bot_search_btn', 'value' => '🔍 Tìm sản phẩm', 'label' => 'Nút Tìm sản phẩm'],
                ['key' => 'bot_track_btn', 'value' => '📦 Tra cứu đơn hàng', 'label' => 'Nút Tra cứu đơn hàng'],
                ['key' => 'bot_blog_btn', 'value' => '📝 Đọc Blog', 'label' => 'Nút Đọc Blog'],
                ['key' => 'bot_feedback_btn', 'value' => '📧 Gửi góp ý', 'label' => 'Nút Gửi góp ý'],
                ['key' => 'bot_contact_btn', 'value' => '📞 Liên hệ', 'label' => 'Nút Liên hệ'],
                ['key' => 'bot_placeholder', 'value' => 'Nhấn chọn hoặc nhập tin nhắn...', 'label' => 'Dòng gợi ý (Placeholder)'],
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
}
