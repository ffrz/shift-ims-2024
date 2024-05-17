<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\SysEvent;
use App\Models\UserGroup;
use App\Models\UserGroupAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserGroupController extends Controller
{
    public function index()
    {
        $items = UserGroup::orderBy('name', 'asc')->get();
        return view('admin.user-group.index', compact('items'));
    }

    public function edit(Request $request, $id = 0)
    {
        $group = $id ? UserGroup::find($id) : new UserGroup();
        if (!$group)
            return redirect('admin/user-group')->with('warning', 'Grup Pengguna tidak ditemukan.');

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

            $acl = (array)$request->post('acl');

            DB::beginTransaction();
            
            $data = ['Old Data' => $group->toArray()];
            $group->fill($request->all());
            $group->save();
            $data['New Data'] = $group->toArray();

            DB::delete('delete from user_group_accesses where group_id = ?', [$group->id]);
            foreach ($acl as $resource => $allowed) {
                $access = new UserGroupAccess();
                $access->group_id = $group->id;
                $access->resource = $resource;
                $access->allow = $allowed;
                $access->save();
            }

            SysEvent::log(
                SysEvent::USER_GROUP_MANAGEMENT,
                ($id == 0 ? 'Tambah' : 'Perbarui') . ' Grup Pengguna',
                'Grup pengguna ' . e($group->name) . ' telah ' . ($id == 0 ? 'dibuat' : 'diperbarui'),
                $data
            );

            DB::commit();

            return redirect('admin/user-group/edit/' . $group->id)->with('info', 'Grup pengguna telah disimpan.');
        }

        $resources = AclResource::all();
        
        return view('admin.user-group.edit', compact('group', 'resources'));
    }

    public function delete($id)
    {
        // fix me, notif kalo grup ga bisa dihapus
        if (!$group = UserGroup::find($id))
            $message = 'Grup pengguna tidak ditemukan.';
        else if ($group->delete($id)) {
            $message = 'Grup pengguna ' . e($group->name) . ' telah dihapus.';
            SysEvent::log(
                SysEvent::USER_GROUP_MANAGEMENT,
                'Hapus Grup Pengguna',
                $message,
                $group->toArray()
            );
        }

        return redirect('admin/user-group')->with('info', $message);
    }
}
