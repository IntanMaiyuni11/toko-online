<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RewardController extends Controller
{
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
                                        <button type="submit" class="dropdown-item text-danger">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>';
                })
                ->rawColumns(['action'])
                ->make();
        }

        return view('pages.admin.rewards.index');
    }

    public function create()
    {
        return view('pages.admin.rewards.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        Reward::create($data);

        return redirect()->route('admin.rewards.index');
    }

    public function edit($id)
    {
        $item = Reward::findOrFail($id);
        return view('pages.admin.rewards.edit', [
            'item' => $item
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $item = Reward::findOrFail($id);
        $item->update($data);

        return redirect()->route('admin.rewards.index');
    }

    public function destroy($id)
    {
        $item = Reward::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.rewards.index');
    }
}