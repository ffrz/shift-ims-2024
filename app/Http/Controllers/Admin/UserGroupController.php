<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SysEvent;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserGroupController extends Controller
{
    public function index()
    {
        $items = UserGroup::orderBy('name', 'asc')->get();
        return view('admin.user-groups.index', compact('items'));
    }

    public function edit(Request $request, $id = 0)
    {
        $group = $id ? UserGroup::find($id) : new UserGroup();
        if (!$group)
            return redirect('admin/user-groups')->with('warning', 'Grup Pengguna tidak ditemukan.');

        if ($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:user_groups,name,' . $request->id . '|max:100',
            ], [
                'name.required' => 'Nama grup harus diisi.',
                'name.unique' => 'Nama grup sudah digunakan.',
                'name.max' => 'Nama grup terlalu panjang, maksimal 100 karakter.',
            ]);

            if ($validator->fails())
                return redirect()->back()->withInput()->withErrors($validator);

            $data = ['Old Data' => $group->toArray()];
            $group->fill($request->all());
            $group->save();
            $data['New Data'] = $group->toArray();

            SysEvent::log(
                SysEvent::USERGROUP_MANAGEMENT,
                ($id == 0 ? 'Tambah' : 'Perbarui') . ' Grup Pengguna',
                'Grup pengguna ' . e($group->name) . ' telah ' . ($id == 0 ? 'dibuat' : 'diperbarui'),
                $data
            );
            
            return redirect('admin/user-groups')->with('info', 'Grup pengguna telah disimpan.');
        }

        return view('admin.user-groups.edit', compact('group'));
    }

    public function delete($id)
    {
        if (!$group = UserGroup::find($id))
            $message = 'Grup pengguna tidak ditemukan.';
        else if ($group->delete($id)) {
            $message = 'Grup pengguna ' . e($group->name) . ' telah dihapus.';
            SysEvent::log(
                SysEvent::USERGROUP_MANAGEMENT,
                'Hapus Grup Pengguna',
                $message,
                $group->toArray()
            );
        }

        return redirect('admin/user-groups')->with('info', $message);
    }
}
