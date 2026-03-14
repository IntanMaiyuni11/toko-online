<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Http\Requests\Admin\UserRequest;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = User::query();

            return DataTables::of($query)
                ->addColumn('action', function ($item) {
                    return '
                        <div class="btn-group">
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle mr-1 mb-1" 
                                    type="button" id="action' .  $item->id . '"
                                        data-toggle="dropdown">
                                        Aksi
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="' . route('admin.user.edit', $item->id) . '">
                                        Sunting
                                    </a>
                                    <form action="' . route('admin.user.destroy', $item->id) . '" method="POST">
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

        return view('pages.admin.user.index');
    }

    public function create()
    {
        return view('pages.admin.user.create');
    }

    public function store(UserRequest $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($request->password);

        if($request->hasFile('photos')) {
            $data['photos'] = $request->file('photos')->store('assets/user', 'public');
        }

        User::create($data);
        return redirect()->route('admin.user.index');
    }

    public function edit($id)
    {
        $item = User::findOrFail($id);
        return view('pages.admin.user.edit', compact('item'));
    }

    public function update(UserRequest $request, $id)
    {
        $data = $request->all();
        $item = User::findOrFail($id);

        if($request->password) {
            $data['password'] = bcrypt($request->password);
        } else {
            unset($data['password']);
        }

        if($request->hasFile('photos')) {
            if($item->photos) Storage::disk('public')->delete($item->photos);
            $data['photos'] = $request->file('photos')->store('assets/user', 'public');
        }

        $item->update($data);
        return redirect()->route('admin.user.index');
    }

    public function destroy($id)
    {
        $item = User::findOrFail($id);
        if($item->photos) Storage::disk('public')->delete($item->photos);
        $item->delete();

        return redirect()->route('admin.user.index');
    }
}