<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\ProductRequest; // Perbaikan: Import Request yang benar
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables; // Perbaikan: Gunakan DataTables (T besar)

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Product::with(['user', 'category']);

            return DataTables::of($query)
                ->addColumn('action', function ($item) {
                    return '
                        <div class="btn-group">
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle mr-1 mb-1" 
                                    type="button" id="action' .  $item->id . '"
                                    data-toggle="dropdown" 
                                    aria-haspopup="true"
                                    aria-expanded="false">
                                    Aksi
                                </button>
                                <div class="dropdown-menu" aria-labelledby="action' .  $item->id . '">
                                    <a class="dropdown-item" href="' . route('admin.product.edit', $item->id) . '">
                                        Sunting
                                    </a>
                                    <form action="' . route('admin.product.destroy', $item->id) . '" method="POST">
                                        ' . method_field('delete') . csrf_field() . '
                                        <button type="submit" class="dropdown-item text-danger">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>';
                })
                // TAMBAHKAN LOGIC STOK DI SINI
                ->editColumn('stock', function ($item) {
                    if ($item->stock <= 0) {
                        return '<span class="badge badge-danger">Habis</span>';
                    } elseif ($item->stock <= 5) {
                        return '<span class="badge badge-warning text-dark">' . $item->stock . ' (Limit)</span>';
                    }
                    return $item->stock;
                })
                ->editColumn('price', function ($item) {
                    return 'Rp' . number_format($item->price, 0, ',', '.');
                })
                ->rawColumns(['action', 'stock']) // Pastikan 'stock' ada di sini agar HTML terbaca
                ->make(true);
        }

        return view('pages.admin.product.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $categories = Category::all();
        
        return view('pages.admin.product.create', [
            'users' => $users,
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $data = $request->validated(); // Perbaikan: Gunakan validated() agar aman

        $data['slug'] = Str::slug($request->name);

        Product::create($data);

        return redirect()->route('admin.product.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $item = Product::with(['category', 'user'])->findOrFail($id);
        $users = User::all();
        $categories = Category::all();
        
        return view('pages.admin.product.edit', [
            'item' => $item,
            'users' => $users,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, $id)
    {
        $data = $request->validated(); // Perbaikan: Gunakan validated()

        $item = Product::findOrFail($id);

        $data['slug'] = Str::slug($request->name);

        $item->update($data);

        return redirect()->route('admin.product.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $item = Product::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.product.index');
    }
}