<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\Customer;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index()
    {
        /** @disregard P1009 */
        if (!Auth::user()->canAccess(AclResource::CUSTOMER_LIST))
            abort(403, 'AKSES DITOLAK');

        $items = Customer::orderBy('name', 'asc')->get();
        return view('admin.customer.index', compact('items'));
    }

    public function edit(Request $request, $id = 0)
    {
        /** @disregard P1009 */
        if ((!$id && !Auth::user()->canAccess(AclResource::ADD_CUSTOMER)) || $id && !Auth::user()->canAccess(AclResource::EDIT_CUSTOMER))
            abort(403, 'AKSES DITOLAK');

        $item = $id ? Customer::find($id) : new Customer();
        if (!$item)
            return redirect('admin/customer')->with('warning', 'Pelanggan tidak ditemukan.');

        if ($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:customers,name,' . $request->id . '|max:100',
            ], [
                'name.required' => 'Nama pelanggan harus diisi.',
                'name.unique' => 'Nama pelanggan sudah digunakan.',
                'name.max' => 'Nama pelanggan terlalu panjang, maksimal 100 karakter.',
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
                UserActivity::CUSTOMER_MANAGEMENT,
                ($id == 0 ? 'Tambah' : 'Perbarui') . ' Pelanggan',
                'Pelanggan ' . e($item->name) . ' telah ' . ($id == 0 ? 'dibuat' : 'diperbarui'),
                $data
            );

            return redirect('admin/customer')->with('info', 'Pelanggan telah disimpan.');
        }

        return view('admin.customer.edit', compact('item'));
    }

    public function delete($id)
    {
        /** @disregard P1009 */
        if (!Auth::user()->canAccess(AclResource::DELETE_CUSTOMER))
            abort(403, 'AKSES DITOLAK');

        // fix me, notif kalo kategori ga bisa dihapus
        if (!$item = Customer::find($id))
            $message = 'Pelanggan tidak ditemukan.';
        else if ($item->delete($id)) {
            $message = 'Pelanggan ' . e($item->name) . ' telah dihapus.';
            UserActivity::log(
                UserActivity::CUSTOMER_MANAGEMENT,
                'Hapus Pelanggan',
                $message,
                $item->toArray()
            );
        }

        return redirect('admin/customer')->with('info', $message);
    }
}
