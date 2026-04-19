<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BotManController;

Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/botman/chat', function() {
    return view('botman.chat');
});
Route::get('/api/products/search', [ProductController::class, 'search'])->name('api.products.search');
Route::get('/feedback', [HomeController::class, 'feedback'])->name('feedback');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

// Cart Routes
Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');

Route::get('/checkout', [\App\Http\Controllers\CartController::class, 'checkout'])->name('order.checkout');
Route::get('/checkout/{slug}', [ProductController::class, 'checkout'])->name('product.checkout');
Route::post('/order', [ProductController::class, 'storeOrder'])->name('order.store');

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'loginForm'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login']);
    Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/products/new', [AdminController::class, 'createProductForm'])->name('admin.product.new');
    Route::post('/product/create', [AdminController::class, 'createProduct'])->name('admin.product.create');
    Route::get('/product/{id}/edit', [AdminController::class, 'editProductForm'])->name('admin.product.edit');
    Route::post('/product/{id}/update', [AdminController::class, 'updateProduct'])->name('admin.product.update');
    Route::post('/product/{id}/toggle-active', [AdminController::class, 'toggleProductActive'])->name('admin.product.toggle');
    Route::post('/product/{id}/delete', [AdminController::class, 'deleteProduct'])->name('admin.product.delete');
    
    Route::get('/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/categories/new', [AdminController::class, 'createCategoryForm'])->name('admin.category.new');
    Route::post('/categories/create', [AdminController::class, 'storeCategory'])->name('admin.category.create');
    Route::post('/categories/{id}/delete', [AdminController::class, 'deleteCategory'])->name('admin.category.delete');

    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::post('/order/{id}/update', [AdminController::class, 'updateOrder'])->name('admin.order.update');

    // Chatbot Config
    Route::get('/bot-settings', [AdminController::class, 'botSettings'])->name('admin.bot.settings');
    Route::post('/bot-settings/update', [AdminController::class, 'updateBotSettings'])->name('admin.bot.update');

    // Blog
    Route::get('/posts', [AdminController::class, 'posts'])->name('admin.posts');
    Route::get('/posts/new', [AdminController::class, 'createPostForm'])->name('admin.post.new');
    Route::post('/posts/create', [AdminController::class, 'storePost'])->name('admin.post.create');
    Route::get('/post/{id}/edit', [AdminController::class, 'editPostForm'])->name('admin.post.edit');
    Route::post('/post/{id}/edit', [AdminController::class, 'updatePost'])->name('admin.post.update');
    Route::post('/posts/{id}/delete', [AdminController::class, 'deletePost'])->name('admin.post.delete');

    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');

    // Sliders
    Route::get('/sliders', [AdminController::class, 'sliders'])->name('admin.sliders');
    Route::get('/sliders/new', [AdminController::class, 'createSliderForm'])->name('admin.slider.new');
    Route::post('/sliders/create', [AdminController::class, 'storeSlider'])->name('admin.slider.create');
    Route::get('/sliders/{id}/edit', [AdminController::class, 'editSliderForm'])->name('admin.slider.edit');
    Route::post('/sliders/{id}/update', [AdminController::class, 'updateSlider'])->name('admin.slider.update');
    Route::post('/sliders/{id}/delete', [AdminController::class, 'deleteSlider'])->name('admin.slider.delete');
});

// AJAX Cart Count for Chatbot Sync
Route::get('/api/cart-count', function() {
    $cart = session()->get('cart', []);
    return response()->json(['count' => count($cart)]);
});

// Blog Public Routes
Route::get('/blog', [App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');

// Feedback/Report Route
Route::post('/feedback', [App\Http\Controllers\AdminController::class, 'storeFeedback'])->name('feedback.store');
