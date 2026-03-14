<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
   public function index(Request $request)
{
    $userId = Auth::id();
    $activeContact = null;
    $messages = [];
    $selectedProduct = null;

    // 1. Ambil daftar user yang pernah berinteraksi chat
    $contacts = User::whereHas('sentMessages', function($q) use ($userId) {
        $q->where('receiver_id', $userId);
    })->orWhereHas('receivedMessages', function($q) use ($userId) {
        $q->where('sender_id', $userId);
    })->where('id', '!=', $userId)->get();

    // 2. Jika klik dari produk/transaksi, ambil data produknya
    if ($request->has('product_id')) {
        $selectedProduct = Product::with('galleries')->find($request->product_id);
    }

    // 3. Jika memilih kontak spesifik (user_id ada di URL)
    if ($request->has('user_id')) {
        $activeContact = User::findOrFail($request->user_id);

        // LOGIKA TAMBAHAN: Jika ini chat baru (belum ada di list contacts), 
        // tambahkan manual ke dalam koleksi agar muncul di sidebar
        if (!$contacts->contains('id', $activeContact->id)) {
            $contacts->push($activeContact);
        }
        
        // Ambil riwayat pesan
        $messages = Message::with(['product.galleries', 'transaction'])
            ->where(function($q) use ($userId, $activeContact) {
                $q->where('sender_id', $userId)->where('receiver_id', $activeContact->id);
            })->orWhere(function($q) use ($userId, $activeContact) {
                $q->where('sender_id', $activeContact->id)->where('receiver_id', $userId);
            })->orderBy('created_at', 'asc')->get();

        // Tandai sudah dibaca
        Message::where('sender_id', $activeContact->id)
               ->where('receiver_id', $userId)
               ->where('is_read', false)
               ->update(['is_read' => true]);
    }

    return view('pages.dashboard-chat', [
        'contacts' => $contacts,
        'messages' => $messages,
        'activeContact' => $activeContact,
        'selectedProduct' => $selectedProduct
    ]);
}

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required',
            'message' => 'required_without_all:image,products_id',
        ]);

        $data = $request->all();
        $data['sender_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('assets/chat', 'public');
        }

        Message::create($data);

        return redirect()->route('dashboard-chat', ['user_id' => $request->receiver_id]);
    }
}