<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\Supplier;
use App\Models\SysEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index()
    {
        /** @disregard P1009 */
        if (!Auth::user()->canAccess(AclResource::SUPPLIER_LIST))
            abort(403, 'AKSES DITOLAK');

        $items = Supplier::orderBy('name', 'asc')->get();
        return view('admin.supplier.index', compact('items'));
    }

    public function edit(Request $request, $id = 0)
    {
        /** @disregard P1009 */
        if ((!$id && !Auth::user()->canAccess(AclResource::ADD_SUPPLIER)) || ($id && !Auth::user()->canAccess(AclResource::EDIT_SUPPLIER)))
            abort(403, 'AKSES DITOLAK');

        $item = $id ? Supplier::find($id) : new Supplier();
        if (!$item)
            return redirect('admin/supplier')->with('warning', 'Pemasok tidak ditemukan.');

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

            SysEvent::log(
                SysEvent::SUPPLIER_MANAGEMENT,
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
        /** @disregard P1009 */
        if (!Auth::user()->canAccess(AclResource::DELETE_SUPPLIER))
            abort(403, 'AKSES DITOLAK');

        // fix me, notif kalo kategori ga bisa dihapus
        if (!$item = Supplier::find($id))
            $message = 'Supplier tidak ditemukan.';
        else if ($item->delete($id)) {
            $message = 'Supplier ' . e($item->name) . ' telah dihapus.';
            SysEvent::log(
                SysEvent::SUPPLIER_MANAGEMENT,
                'Hapus Supplier',
                $message,
                $item->toArray()
            );
        }

        return redirect('admin/supplier')->with('info', $message);
    }
}
