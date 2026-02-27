<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductGallery;
use App\Models\Product; 
use App\Http\Requests\Admin\ProductGalleryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables; // Pastikan Facade di-import

class ProductGalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ProductGallery::with(['product']);

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
                                    <form action="' . route('admin.productgallery.destroy', $item->id) . '" method="POST">
                                        ' . method_field('delete') . csrf_field() . '
                                        <button type="submit" class="dropdown-item text-danger">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>';
                })
                ->editColumn('photos', function ($item) {
                    return $item->photos ? '<img src="' . Storage::url($item->photos) . '" style="max-height: 80px; border-radius: 5px;"/>' : '';
                })
                ->rawColumns(['action','photos'])
                ->make(true);
        }

        return view('pages.admin.product-gallery.index');
    }

    public function create()
    {
        $products = Product::all();
        
        return view('pages.admin.product-gallery.create', [
            'products' => $products
        ]);
    }

    public function store(ProductGalleryRequest $request)
    {
        $data = $request->validated(); // Gunakan validated() lebih aman di Laravel 12

        if ($request->hasFile('photos')) {
            $data['photos'] = $request->file('photos')->store('assets/product', 'public');
        }

        ProductGallery::create($data);

        return redirect()->route('admin.productgallery.index');
    }

    public function destroy($id)
    {
        $item = ProductGallery::findOrFail($id);
        
        // Opsional: Hapus file fisik di storage saat data dihapus
        if ($item->photos) {
            Storage::disk('public')->delete($item->photos);
        }
        
        $item->delete();

        return redirect()->route('admin.productgallery.index');
    }
}