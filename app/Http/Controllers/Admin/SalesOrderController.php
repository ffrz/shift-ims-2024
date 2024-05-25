<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalesOrderController extends Controller
{

    public function index()
    {
        $items = SalesOrder::orderBy('id', 'desc')->get();
        $filter = [];
        return view('admin.sales-order.index', compact('items', 'filter'));
    }

    public function create()
    {
        $item = new SalesOrder();
        $item->date = date('Y-m-d');
        $item->save();
        return redirect('admin/sales-order/edit/' . $item->id)->with('info', 'Order penjualan telah dibuat.');
    }

    public function edit(Request $request, $id = 0)
    {
        $item = SalesOrder::findOrFail($id);
        if (!$item)
            return redirect('admin/sales-order')->with('warning', 'Order penjualan tidak ditemukan.');

        if ($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:product_categories,name,' . $request->id . '|max:100',
            ], [
                'name.required' => 'Nama kategori harus diisi.',
                'name.unique' => 'Nama kategori sudah digunakan.',
                'name.max' => 'Nama kategori terlalu panjang, maksimal 100 karakter.',
            ]);

            if ($validator->fails())
                return redirect()->back()->withInput()->withErrors($validator);

            $data = ['Old Data' => $item->toArray()];
            $item->fill($request->all());
            $item->save();
            $data['New Data'] = $item->toArray();

            UserActivity::log(
                UserActivity::PRODUCT_CATEGORY_MANAGEMENT,
                ($id == 0 ? 'Tambah' : 'Perbarui') . ' Kategori Produk',
                'Kategori Produk ' . e($item->name) . ' telah ' . ($id == 0 ? 'dibuat' : 'diperbarui'),
                $data
            );

            return redirect('admin/purchase-order')->with('info', 'Kategori produk telah disimpan.');
        }

        $tmp_products = Product::select(['id', 'code', 'description', 'stock', 'uom', 'price', 'barcode'])
            ->orderBy('code', 'asc')->get();
        $products = [];
        $barcodes = [];
        foreach ($tmp_products as $product) {
            $p = $product->toArray();
            $p['pid'] = $product->idFormatted();
            $products[$product->idFormatted()] = $p;
            if (!empty($product->barcode)) {
                $barcodes[$product->barcode] = $product->idFormatted();
            }
        }
        $customers = Customer::orderBy('name', 'asc')->get();
        return view('admin.sales-order.edit', compact('item', 'customers', 'products', 'barcodes'));
    }

    public function saveDetail(Request $request)
    {
        $order = SalesOrder::findOrFail($request->order_id);
        DB::beginTransaction();
        DB::delete('delete from sales_order_details where order_id = ?', [$order->id]);
        foreach ($request->items as $k => $item) {
            $d = new SalesOrderDetail();
            $d->id = $k + 1;
            $d->order_id = $order->id;
            $d->product_id = $item['id'];
            $d->quantity = $item['qty'];
            $d->price = $item['price'];
            $d->save();
        }
        DB::commit();
        return response()->json([
            'status' => 'success', 
            'data' =>[], 
            'message' => 'Information saved successfully!'
        ], 200);
    }

    public function delete($id)
    {
        // fix me, notif kalo kategori ga bisa dihapus
        if (!$item = ProductCategory::find($id))
            $message = 'Kategori tidak ditemukan.';
        else if ($item->delete($id)) {
            $message = 'Kategori ' . e($item->name) . ' telah dihapus.';
            UserActivity::log(
                UserActivity::PRODUCT_CATEGORY_MANAGEMENT,
                'Hapus Kategori',
                $message,
                $item->toArray()
            );
        }

        return redirect('admin/purchase-order')->with('info', $message);
    }
}
