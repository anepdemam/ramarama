<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => false, // Require moderation
        ]);

        return back()->with('success', 'Thank you! Your review has been submitted and is awaiting moderation.');
    }

    public function index(Request $request)
    {
        $query = Review::with(['user', 'product']);

        if ($request->filled('id')) {
            $id = $request->id;
            $query->where(function ($q) use ($id) {
                $q->where('id', $id)
                    ->orWhere('product_id', $id);
            });
        }

        if ($request->filled('item')) {
            $item = $request->item;
            $query->whereHas('product', function ($q) use ($item) {
                $q->where('name', 'like', '%' . $item . '%');
            });
        }

        if ($request->filled('category')) {
            $category = $request->category;
            $query->whereHas('product', function ($q) use ($category) {
                $q->where('category', $category);
            });
        }

        $reviews = $query->latest()->get();
        $categories = Product::distinct()->pluck('category');

        return view('admin.reviews.index', compact('reviews', 'categories'));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        return back()->with('success', 'Review approved successfully.');
    }

    public function delete(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted.');
    }
}
