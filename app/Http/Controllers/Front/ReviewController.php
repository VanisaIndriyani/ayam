<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|max:500',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'content' => $request->content,
            'is_visible' => true,
        ]);

        return redirect()->back()->with('success', 'Terima kasih atas ulasan Anda!');
    }
}
