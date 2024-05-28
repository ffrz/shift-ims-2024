<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\Party;
use App\Models\Supplier;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index()
    {
        ensure_user_can_access(AclResource::SUPPLIER_LIST);

        $q = Supplier::query();
        $items = $q->orderBy('name', 'asc')->paginate(10);
        return view('admin.supplier.index', compact('items'));
    }

    public function edit(Request $request, $id = 0)
    {
        if (!$id) {
            ensure_user_can_access(AclResource::ADD_SUPPLIER);
            $item = new Supplier();
            $item->id2 = Party::getNextId2(Party::TYPE_SUPPLIER);
            $item->active = true;
        } else {
            ensure_user_can_access(AclResource::EDIT_SUPPLIER);
            $item = Supplier::find($id);
            if (!$item) {
                return redirect('admin/supplier')->with('warning', 'Pemasok tidak ditemukan.');
            }
        }

        if ($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:suppliers,name,' . $request->id . '|max:100',
            ], [
                'name.required' => 'Nama pemasok harus diisi.',
                'name.unique' => 'Nama pemasok sudah digunakan.',
                'name.max' => 'Nama pemasok terlalu panjang, maksimal 100 karakter.',
            ]);

            if ($validator->fails())
                return redirect()->back()->withInput()->withErrors($validator);

            $data = ['Old Data' => $item->toArray()];

            $tmpData = $request->all();
            if (empty($tmpData['active']))
                $tmpData['active'] = false;
            $item->fill($tmpData);
            $item->save();
            $data['New Data'] = $item->toArray();

            UserActivity::log(
                UserActivity::SUPPLIER_MANAGEMENT,
                ($id == 0 ? 'Tambah' : 'Perbarui') . ' Supplier',
                'Supplier ' . e($item->name) . ' telah ' . ($id == 0 ? 'dibuat' : 'diperbarui'),
                $data
            );

            return redirect('admin/supplier')->with('info', 'Supplier telah disimpan.');
        }

        return view('admin.supplier.edit', compact('item'));
    }

    public function delete($id)
    {
        ensure_user_can_access(AclResource::DELETE_SUPPLIER);

        // fix me, notif kalo kategori ga bisa dihapus
        if (!$item = Supplier::find($id)) {
            $message = 'Supplier tidak ditemukan.';
        } else if ($item->delete($id)) {
            $message = 'Supplier ' . e($item->name) . ' telah dihapus.';
            UserActivity::log(
                UserActivity::SUPPLIER_MANAGEMENT,
                'Hapus Supplier',
                $message,
                $item->toArray()
            );
        }

        return redirect('admin/supplier')->with('info', $message);
    }
}
