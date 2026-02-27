<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Category;
use App\Providers\AppServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Menampilkan form registrasi (Ganti showRegistrationForm)
     */
    public function create(): View
    {
        $categories = Category::all();
        return view('auth.register', [
            'categories' => $categories
        ]);
    }

    /**
     * Proses pembuatan User baru
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi data (Pindahan dari function validator)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'store_name' => ['nullable', 'string', 'max:255'],
            'categories_id' => ['nullable', 'integer', 'exists:categories,id'],
            'is_store_open' => ['required'],
        ]);

        // Create User (Pindahan dari function create)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'store_name' => $request->store_name ?? '',
            'categories_id' => $request->categories_id ?? NULL,
            'store_status' => $request->is_store_open == 'true' ? 1 : 0,
            'roles' => 'USER', // Default role
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect ke home sesuai permintaanmu sebelumnya
        return redirect(AppServiceProvider::HOME);
    }

    /**
     * Menampilkan halaman sukses setelah registrasi
     */
    public function success()
    {
        return view('auth.success');
    }

    /**
     * Cek ketersediaan email via AJAX/Axios
     */
    public function check(Request $request)
    {
        return User::where('email', $request->email)->count() > 0 
                ? 'Unavailable' 
                : 'Available';
    }
}