<?php

namespace App\Http\Controllers;

use App\Models\Category; 
use App\Models\User;     
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardSettingController extends Controller
{
    public function store()
    {
        $user = Auth::user();
        $categories = Category::all();

        return view('pages.dashboard-settings',[
            'user' => $user,
            'categories' => $categories
        ]);
    }

    public function account()
    {
        $user = Auth::user();

        return view('pages.dashboard-account',[
            'user' => $user
        ]);
    }

    public function update(Request $request, $redirect)
    {
        $data = $request->all();
        
        /** @var \App\Models\User $item */
        $item = Auth::user();

        // Logika Hapus Foto
        if ($request->delete_photo == "1") {
            if ($item->photos) {
                Storage::disk('public')->delete($item->photos);
            }
            $data['photos'] = null; 
        }

        // Logika Upload Foto Baru
        if ($request->hasFile('photos')) {
            // Hapus foto lama jika ada sebelum ganti yang baru
            if ($item->photos) {
                Storage::disk('public')->delete($item->photos);
            }
            $data['photos'] = $request->file('photos')->store('assets/user', 'public');
        }

        // Update data user
        $item->update($data);

        return redirect()->route($redirect);
    }
}