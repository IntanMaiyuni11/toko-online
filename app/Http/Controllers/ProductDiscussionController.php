<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductDiscussion;
use Illuminate\Support\Facades\Auth;

class ProductDiscussionController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|min:5'
        ]);

        ProductDiscussion::create([
            'products_id' => $id,
            'users_id' => Auth::user()->id,
            'comment' => $request->comment,
            'parent_id' => $request->parent_id ?? null, // Bisa untuk tanya atau jawab
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil dikirim');
    }
}