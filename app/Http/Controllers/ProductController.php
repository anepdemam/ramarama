<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('category') && $request->get('category') != '') {
            $query->where('category', $request->get('category'));
        }

        if ($request->get('sort') == 'low_to_high') {
            $query->orderBy('price', 'asc');
        } elseif ($request->get('sort') == 'high_to_low') {
            $query->orderBy('price', 'desc');
        }

        $products = $query->get();
        $categories = Product::select('category')->distinct()->get();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $reviews = $product->reviews()->where('is_approved', true)->with('user')->latest()->get();
        $averageRating = $reviews->avg('rating');

        return view('products.show', compact('product', 'reviews', 'averageRating'));
    }

    public function suggestions(Request $request)
    {
        $query = $request->get('query');
        $suggestions = Product::where('name', 'like', "%{$query}%")
            ->limit(6)
            ->get(['id', 'name', 'price', 'images', 'slug'])
            ->map(function ($product) {
                return [
                    'name' => $product->name,
                    'price' => number_format($product->price, 2),
                    'image' => !empty($product->images) ? asset($product->images[0]) : null,
                    'url' => route('products.show', $product->slug ?? $product->id),
                ];
            });

        return response()->json($suggestions);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
