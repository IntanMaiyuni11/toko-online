<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\Admin\CategoryRequest;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // ✅ withTrashed() supaya data yang sudah dihapus tetap ditarik/muncul
            $query = Category::withTrashed(); 

            return DataTables::of($query)
                ->addColumn('photo', function ($item) {
    // Cek apakah ada foto
    if ($item->photo) {
        // Link yang membungkus gambar
        // asset('storage/' . $item->photo) akan menghasilkan URL seperti di screenshot kamu
        return '
            <a href="' . asset('storage/' . $item->photo) . '" target="_blank">
                <img src="' . asset('storage/' . $item->photo) . '" style="max-height: 40px; border-radius: 5px;">
            </a>
        ';
    }
    return 'No Image';
})
                ->addColumn('action', function ($item) {
                    // Jika data statusnya terhapus (Soft Delete)
                    if($item->trashed()) {
                        return '
                            <a href="'.route('admin.category.restore', $item->id).'" class="btn btn-success btn-sm">
                                Restore (Tarik Kembali)
                            </a>
                        ';
                    }

                    // Jika data normal
                    return '
                        <a href="'.route('admin.category.edit',$item->id).'" class="btn btn-info btn-sm">Edit</a>
                        <form action="'.route('admin.category.destroy',$item->id).'" method="POST" style="display:inline;">
                            '.csrf_field().' '.method_field('DELETE').'
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    ';
                })
                ->rawColumns(['photo','action'])
                ->make(true);
        }

        return view('pages.admin.category.index');
    }

    // ✅ Method baru untuk mengembalikan data
    public function restore($id)
    {
        $item = Category::onlyTrashed()->findOrFail($id);
        $item->restore();

        return redirect()->route('admin.category.index')
                         ->with('success', 'Kategori berhasil dipulihkan!');
    }

    public function create()
    {
        return view('pages.admin.category.create');
    }

    public function store(CategoryRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('assets/category', 'public');
        }

        Category::create($data);
        return redirect()->route('admin.category.index')->with('success', 'Berhasil!');
    }

    public function edit($id)
    {
        $item = Category::findOrFail($id);
        return view('pages.admin.category.edit', ['item' => $item]);
    }

    public function update(CategoryRequest $request, $id)
    {
        $item = Category::findOrFail($id);
        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('assets/category', 'public');
        }

        $item->update($data);
        return redirect()->route('admin.category.index');
    }

    public function destroy($id)
    {
        $item = Category::findOrFail($id);
        $item->delete(); // Ini akan melakukan Soft Delete

        return redirect()->route('admin.category.index');
    }
}