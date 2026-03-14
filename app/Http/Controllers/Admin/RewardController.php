<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RewardController extends Controller
{
    /**
     * Menampilkan daftar reward menggunakan DataTables.
     */
    public function index()
    {
        if (request()->ajax()) {
            $query = Reward::query();

            return DataTables::of($query)
                ->addColumn('action', function ($item) {
                    return '
                        <div class="btn-group">
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle mr-1 mb-1" 
                                    type="button" id="action' .  $item->id . '"
                                    data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    Aksi
                                </button>
                                <div class="dropdown-menu" aria-labelledby="action' .  $item->id . '">
                                    <a class="dropdown-item" href="' . route('admin.rewards.edit', $item->id) . '">
                                        Sunting
                                    </a>
                                    <form action="' . route('admin.rewards.destroy', $item->id) . '" method="POST">
                                        ' . method_field('delete') . csrf_field() . '
                                        <button type="submit" class="dropdown-item text-danger" 
                                            onclick="return confirm(\'Apakah Anda yakin ingin menghapus reward ini?\')">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>';
                })
                ->editColumn('points', function($item) {
                    return number_format($item->points) . ' Poin';
                })
                ->editColumn('discount_amount', function($item) {
                    return 'Rp ' . number_format($item->discount_amount);
                })
                ->rawColumns(['action'])
                ->make();
        }

        return view('pages.admin.rewards.index');
    }

    /**
     * Menampilkan halaman tambah reward.
     */
    public function create()
    {
        return view('pages.admin.rewards.create');
    }

    /**
     * Menyimpan data reward baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'points' => 'required|integer|min:1',
            'discount_amount' => 'required|integer|min:0',
        ]);

        $data = $request->all();
        
        Reward::create($data);

        return redirect()->route('admin.rewards.index')
                         ->with('success', 'Reward berhasil ditambahkan!');
    }

    /**
     * Menampilkan halaman edit reward.
     */
    public function edit($id)
    {
        $item = Reward::findOrFail($id);
        
        return view('pages.admin.rewards.edit', [
            'item' => $item
        ]);
    }

    /**
     * Memperbarui data reward di database.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'points' => 'required|integer|min:1',
            'discount_amount' => 'required|integer|min:0',
        ]);

        $data = $request->all();
        $item = Reward::findOrFail($id);
        
        $item->update($data);

        return redirect()->route('admin.rewards.index')
                         ->with('success', 'Reward berhasil diperbarui!');
    }

    /**
     * Menghapus data reward.
     */
    public function destroy($id)
    {
        $item = Reward::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.rewards.index')
                         ->with('success', 'Reward berhasil dihapus!');
    }
}