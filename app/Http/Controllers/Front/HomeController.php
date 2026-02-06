<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;

class HomeController extends Controller
{
    public function index()
    {
        // Semua produk aktif → untuk slider landing page
        $products = Product::where('is_active', 1)
            ->orderBy('id', 'desc')
            ->get();

        // Produk terbaru
        $newestProducts = Product::where('is_active', 1)
            ->latest()
            ->take(8)
            ->get();

        // Best seller
        $bestSellerProducts = Product::where('is_active', 1)
            ->orderBy('sold_count', 'desc')
            ->take(8)
            ->get();

        // Reviews
        $reviews = Review::where('is_visible', true)
            ->with('user')
            ->latest()
            ->take(6)
            ->get();

        return view('welcome', compact(
            'products',
            'newestProducts',
            'bestSellerProducts',
            'reviews'
        ));
    }
}
