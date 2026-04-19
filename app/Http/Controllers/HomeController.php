<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Slider::where('is_active', true)->orderBy('position', 'desc')->get();
        $featuredProducts = Product::where('is_active', true)->where('is_featured', true)->take(8)->get();
        $categories = Category::has('products')->get();
        $latestPosts = \App\Models\Post::where('is_published', true)->latest()->take(3)->get();
        return view('home', compact('sliders', 'featuredProducts', 'categories', 'latestPosts'));
    }

    public function feedback()
    {
        return view('feedback');
    }
}
