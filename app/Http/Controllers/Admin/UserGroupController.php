<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\UserActivity;
use App\Models\UserGroup;
use App\Models\UserGroupAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserGroupController extends Controller
{
    public function __construct()
    {
        ensure_user_can_access(AclResource::USER_GROUP_MANAGEMENT);
    }

    public function index()
    {
        $items = UserGroup::orderBy('name', 'asc')->paginate(10);
        return view('admin.user-group.index', compact('items'));
    }

    public function edit(Request $request, $id = 0)
    {
        $item = $id ? UserGroup::find($id) : new UserGroup();
        if (!$item)
            return redirect('admin/user-group')->with('warning', 'Grup Pengguna tidak ditemukan.');

        if ($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:user_groups,name,' . $request->id . '|max:100',
            ], [
                'name.required' => 'Nama grup harus diisi.',
                'name.unique' => 'Nama grup sudah digunakan.',
                'name.max' => 'Nama grup terlalu panjang, maksimal 100 karakter.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $acl = (array)$request->post('acl');

            DB::beginTransaction();

            $data = ['Old Data' => $item->toArray()];
            $item->fill($request->all());
            $item->save();
            $data['New Data'] = $item->toArray();

            DB::delete('delete from user_group_accesses where group_id = ?', [$item->id]);
            foreach ($acl as $resource => $allowed) {
                $access = new UserGroupAccess();
                $access->group_id = $item->id;
                $access->resource = $resource;
                $access->allow = $allowed;
                $access->save();
            }

            UserActivity::log(
                UserActivity::USER_GROUP_MANAGEMENT,
                ($id == 0 ? 'Tambah' : 'Perbarui') . ' Grup Pengguna',
                'Grup pengguna ' . e($item->name) . ' telah ' . ($id == 0 ? 'dibuat' : 'diperbarui'),
                $data
            );

            DB::commit();

            return redirect('admin/user-group/edit/' . $item->id)->with('info', 'Grup pengguna telah disimpan.');
        }

        $resources = AclResource::all();

        return view('admin.user-group.edit', compact('item', 'resources'));
    }

    public function delete($id)
    {
        if (!$item = UserGroup::find($id))
            $message = 'Grup pengguna tidak ditemukan.';
        else if ($item->delete($id)) {
            $message = 'Grup pengguna ' . e($item->name) . ' telah dihapus.';
            UserActivity::log(
                UserActivity::USER_GROUP_MANAGEMENT,
                'Hapus Grup Pengguna',
                $message,
                $item->toArray()
            );
        }

        return redirect('admin/user-group')->with('info', $message);
    }
}
