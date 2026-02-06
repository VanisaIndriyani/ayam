<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
{
    $products = Product::with('images')
        ->where('is_active', true)
        ->latest()
        ->paginate(12);

    return view('front.products.index', compact('products'));
}

    
    public function show($slug)
    {
        $product = Product::with('images')->where('slug', $slug)->firstOrFail();

        return view('front.products.show', compact('product'));
    }
}
