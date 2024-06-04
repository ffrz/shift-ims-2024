<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\Party;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\StockUpdate;
use App\Models\StockUpdateDetail;
use App\Models\Supplier;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Number;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        ensure_user_can_access(AclResource::PRODUCT_LIST);

        $filter = [
            'type' => (int)$request->get('type', $request->session()->get('product.filter.type', -1)),
            'active' => (int)$request->get('active', $request->session()->get('product.filter.active', -1)),
            'category_id' => (int)$request->get('category_id', $request->session()->get('product.filter.category_id', -1)),
            'supplier_id' => (int)$request->get('supplier_id', $request->session()->get('product.filter.supplier_id', -1)),
            'stock_status' => (int)$request->get('stock_status', $request->session()->get('product.filter.stock_status', -1)),
            'search' => $request->get('search'),
        ];

        $q = Product::query();

        if ($filter['type'] != -1) {
            $q->where('type', '=', $filter['type']);
        }
        if ($filter['active'] != -1) {
            $q->where('active', '=', $filter['active']);
        }
        if ($filter['category_id'] != -1) {
            $q->where('category_id', '=', $filter['category_id']);
        }
        if ($filter['supplier_id'] != -1) {
            $q->where('supplier_id', '=', $filter['supplier_id']);
        }

        if ($filter['stock_status'] == 0) {
            $q->where('stock', '=', 0);
        }
        else if ($filter['stock_status'] == 1) {
            $q->whereRaw('stock < minimum_stock');
        }

        if (!empty($filter['search'])) {
            $q->where('code', 'like', '%' . $filter['search'] . '%');
            $q->orWhere('description', 'like', '%' . $filter['search'] . '%');
        }

        $categories = ProductCategory::orderBy('name', 'asc')->get();
        $suppliers = Supplier::orderBy('name', 'asc')->get();
        $items = $q->with(['category', 'supplier'])
            ->orderBy('code', 'asc')
            ->paginate(10);

        return view('admin.product.index', compact('items', 'filter', 'suppliers', 'categories'));
    }

    public function edit(Request $request, $id = 0)
    {
        if (!$id) {
            ensure_user_can_access(AclResource::ADD_PRODUCT);
            $item = new Product();
            $item->active = true;
            $item->price = 0;
            $item->cost = 0;
            $item->stock = 0;
        } else {
            ensure_user_can_access(AclResource::EDIT_PRODUCT);
            $item = Product::find($id);
            if (!$item) {
                return redirect('admin/product')->with('warning', 'Produk tidak ditemukan.');
            }
        }

        if ($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'code' => 'required|unique:products,code,' . $id . '|max:100',
            ], [
                'code.required' => 'Nama / kode produk harus diisi.',
                'code.unique' => 'Nama / kode produk sudah digunakan.',
                'code.max' => 'Nama / kode produk terlalu panjang, maksimal 100 karakter.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $data = ['Old Data' => $item->toArray()];
            $newData = $request->all();
            
            $initial_stock = $item->stock;
            $new_stock = $newData['stock'];

            DB::beginTransaction();

            if (empty($newData['category_id']) || $newData['category_id'] == -1) {
                $newData['category_id'] = null;
            }

            if (empty($newData['supplier_id']) || $newData['supplier_id'] == -1) {
                $newData['supplier_id'] = null;
            }

            fill_with_default_value($newData, ['active', 'stock', 'cost', 'price'], 0.);
            
            $newData['stock'] = numberFromInput($newData['stock']);
            $newData['cost'] = numberFromInput($newData['cost']);
            $newData['price'] = numberFromInput($newData['price']);
            
            $item->fill($newData);
            $item->save();
            
            if ($new_stock != $initial_stock) {
                $qty = $new_stock - $initial_stock;
                $update = new StockUpdate();
                $update->type = StockUpdate::TYPE_SINGLE_ADJUSTMENT;
                $update->id2 = StockUpdate::getNextId2($update->type);
                $update->total_cost = $qty * $item->cost;
                $update->total_price = $qty * $item->price;
                $update->open();
                $update->close(StockUpdate::STATUS_COMPLETED);
                $update->save();
                
                $detail = new StockUpdateDetail();
                $detail->id = 1;
                $detail->update_id = $update->id;
                $detail->product_id = $item->id;
                $detail->quantity = $qty;
                $detail->cost = $item->cost;
                $detail->price = $item->price;
                $detail->save();
            }

            $data['New Data'] = $item->toArray();

            UserActivity::log(
                UserActivity::PRODUCT_MANAGEMENT,
                ($id == 0 ? 'Tambah' : 'Perbarui') . ' Produk',
                'Produk ' . e($item->name) . ' telah ' . ($id == 0 ? 'dibuat' : 'diperbarui'),
                $data
            );

            DB::commit();

            return redirect('admin/product')->with('info', 'Produk telah disimpan.');
        }

        $categories = ProductCategory::orderBy('name', 'asc')->get();
        $suppliers = Supplier::where('type', Party::TYPE_SUPPLIER)->orderBy('name', 'asc')->get();
        return view('admin.product.edit', compact('item', 'categories', 'suppliers'));
    }

    public function duplicate(Request $request, $sourceId)
    {
        ensure_user_can_access(AclResource::ADD_PRODUCT);

        $item = Product::findOrFail($sourceId);
        $item = $item->replicate();
        $item->id = 0;

        $categories = ProductCategory::orderBy('name', 'asc')->get();
        $suppliers = Supplier::orderBy('name', 'asc')->get();
        return view('admin.product.edit', compact('item', 'categories', 'suppliers'));
    }

    public function delete(Request $request, $id)
    {
        ensure_user_can_access(AclResource::DELETE_PRODUCT);

        if ($request->force === 'true') {
            $item = Product::withTrashed()->findOrFail($id);
            $msg = ' telah dihapus selamanya.';
            $action = 'forceDelete';
        } else {
            $item = Product::findOrFail($id);
            $msg = ' telah dipindahkan ke tong sampah.';
            $action = 'delete';
        }

        if (!$item) {
            $message = 'Produk tidak ditemukan.';
        } else if ($item->$action($id)) {
            $message = 'Produk ' . e($item->name) . $msg;
            UserActivity::log(
                UserActivity::PRODUCT_MANAGEMENT,
                'Hapus Produk',
                $message,
                $item->toArray()
            );
        }

        return redirect('admin/product')->with('info', $message);
    }

    public function restore($id)
    {
        ensure_user_can_access(AclResource::DELETE_PRODUCT);

        if (!$item = Product::withTrashed()->find($id))
            $message = 'Produk tidak ditemukan.';
        else {
            $item->restore();
            $message = 'Produk #' . e($item->idFormatted()) . ' telah dipulihkan.';
            UserActivity::log(
                UserActivity::PRODUCT_MANAGEMENT,
                'Pulihkan Produk',
                $message,
                $item->toArray()
            );
        }

        return redirect('admin/product')->with('info', $message);
    }
}
