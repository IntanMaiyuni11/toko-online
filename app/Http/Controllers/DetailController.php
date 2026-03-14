<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class DetailController extends Controller
{
    public function index(Request $request, $id)
    {
        $product = Product::with(['galleries', 'user', 'reviews.user', 'discussions.replies'])
            ->where('slug', $id)
            ->firstOrFail();
        
        return view('pages.detail', [
            'product' => $product
        ]);
    }

    public function add(Request $request, $id)
    {
        $data = [
            'products_id' => $id,
            'users_id' => Auth::user()->id
        ];

        Cart::create($data);

        return redirect()->route('cart');
    }

    public function review(Request $request, $id)
{
    $request->validate([
        'comment' => 'required|min:5',
        'rating' => 'required|integer|min:1|max:5',
    ]);

    \App\Models\ProductReview::create([
        'products_id' => $id,
        'users_id' => Auth::id(),
        'comment' => $request->comment,
        'rating' => $request->rating,
    ]);

    return back()->with('success', 'Terima kasih atas ulasan Anda!');
}
}
