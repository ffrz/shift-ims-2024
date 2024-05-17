<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SysEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $items = Product::orderBy('code', 'asc')->get();
        return view('admin.product.index', compact('items'));
    }

    public function edit(Request $request, $id = 0)
    {
        $item = $id ? Product::find($id) : new Product();
        if (!$item)
            return redirect('admin/product')->with('warning', 'Produk tidak ditemukan.');

        if ($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:product_categories,name,' . $request->id . '|max:100',
            ], [
                'name.required' => 'Nama produk harus diisi.',
                'name.unique' => 'Nama produk sudah digunakan.',
                'name.max' => 'Nama produk terlalu panjang, maksimal 100 karakter.',
            ]);

            if ($validator->fails())
                return redirect()->back()->withInput()->withErrors($validator);

            $data = ['Old Data' => $item->toArray()];
            $item->fill($request->all());
            $item->save();
            $data['New Data'] = $item->toArray();

            SysEvent::log(
                SysEvent::PRODUCT_MANAGEMENT,
                ($id == 0 ? 'Tambah' : 'Perbarui') . ' Produk',
                'Produk ' . e($item->name) . ' telah ' . ($id == 0 ? 'dibuat' : 'diperbarui'),
                $data
            );

            return redirect('admin/product')->with('info', 'Produk telah disimpan.');
        }

        return view('admin.product.edit', compact('item'));
    }

    public function delete($id)
    {
        // fix me, notif kalo kategori ga bisa dihapus
        if (!$item = Product::find($id))
            $message = 'Produk tidak ditemukan.';
        else if ($item->delete($id)) {
            $message = 'Produk ' . e($item->name) . ' telah dihapus.';
            SysEvent::log(
                SysEvent::PRODUCT_MANAGEMENT,
                'Hapus Produk',
                $message,
                $item->toArray()
            );
        }

        return redirect('admin/product')->with('info', $message);
    }
}
