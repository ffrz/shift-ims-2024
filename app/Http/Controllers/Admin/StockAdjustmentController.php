<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\StockUpdate;
use App\Models\StockUpdateDetail;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StockAdjustmentController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $items = StockUpdate::where('type', '=', StockUpdate::TYPE_MASS_ADJUSTMENT)->get();
        return view('admin.stock-adjustment.index', compact('items'));
    }

    public function create(Request $request)
    {
        $item = new StockUpdate();
        $item->date = date('Y-m-d');
        $item->type = StockUpdate::TYPE_MASS_ADJUSTMENT;
        $item->status = StockUpdate::STATUS_OPEN;
        $item->id2 = StockUpdate::getNextId2($item->type);

        if ($request->method() == 'POST') {
            $action = $request->get('action');
            $product_ids = array_keys($request->get('product_ids'));
            $products = DB::table('products')->whereIn('id', $product_ids)->orderBy('code', 'asc')->get();

            DB::beginTransaction();
            $item->save();
            foreach ($products as $num => $product) {
                $subitem = new StockUpdateDetail();
                $subitem->id = $num + 1;
                $subitem->update_id = $item->id;
                $subitem->product_id = $product->id;
                $subitem->quantity = 0;
                $subitem->cost = $product->cost;
                $subitem->price = $product->price;
                $subitem->save();
            }
            DB::commit();

            return redirect(url('admin/stock-adjustment/edit/' . $item->id))->with('info', 'Kartu stok telah dibuat');
        }

        $items = Product::orderBy('code', 'asc')->get();
        return view('admin.stock-adjustment.create', compact('item', 'items'));
    }

    public function edit(Request $request, $id)
    {
        $item = StockUpdate::find($id);
        $subitems = StockUpdateDetail::with(['product'])->where('update_id', '=', $item->id)->orderBy('id', 'asc')->get();
        if ($request->method() == 'POST') {
            $action = $request->action;
            if ($action == 'cancel') {
                $item->status = StockUpdate::STATUS_CANCELED;
                $item->save();
                return redirect('admin/stock-adjustment/')->with('info', 'Stok opname telah dibatalkan.');
            }

            $stocks = $request->stocks;

            if ($action == 'complete') {
                $products = Product::whereIn('id', array_keys($stocks))->get();
                $productByIds = [];
                foreach ($products as $product) {
                    $productByIds[$product->id] = $product;
                }
            }

            DB::beginTransaction();
            $new_item_id = 1;
            $total_cost = 0;
            $total_price = 0;
            foreach ($stocks as $id => $real_stock) {
                $subitem = $subitems[$id - 1];

                $diff = $real_stock - $subitem->product->stock;
                $subitem->quantity = $diff;

                if ($action == 'complete') {
                    $product = $productByIds[$subitem->product_id];
                    $skip_item = $product->stock == $real_stock;
                    $product->stock = $real_stock;
                    $product->save();
                }

                if (!empty($skip_item)) {
                    $subitem->delete();
                } else {
                    $subitem->id = $new_item_id;
                    $subitem->save();
                    $new_item_id++;
                }

                $total_cost += $diff * $subitem->product->cost;
                $total_price += $diff * $subitem->product->price;
            }

            if ($action == 'complete') {
                $item->status = StockUpdate::STATUS_COMPLETED;
            }

            $item->total_cost = $total_cost;
            $item->total_price = $total_price;
            $item->save();
            DB::commit();

            if ($action == 'complete') {
                return redirect('admin/stock-adjustment/')->with('info', 'Stok opname telah selesai dan berhasil disimpan.');
            }

            return redirect('admin/stock-adjustment/edit/' . $item->id)->with('info', 'Perubahan telah disimpan.');
        }
        return view('admin.stock-adjustment.edit', compact('subitems', 'item'));
    }

    public function detail(Request $request, $id)
    {
        $item = StockUpdate::find($id);
        $details = StockUpdateDetail::with(['product'])->where('update_id', '=', $item->id)->get();
        return view('admin.stock-adjustment.detail', compact('item', 'details'));
    }

    public function delete($id)
    {
        if (!$item = StockUpdate::find($id))
            $message = 'Penyesuaian stok tidak ditemukan.';
        else {
            $details = StockUpdateDetail::where('update_id', '=', $item->id)->get();
            $quantities = [];
            foreach ($details as $detail) {
                $quantities[$detail->product_id] = $detail->quantity;
            }
            $products = Product::whereIn('id', array_keys($quantities))->get();

            DB::beginTransaction();
            if ($item->status == StockUpdate::STATUS_COMPLETED) { // restore stok hanya jika sudah diselesaikan
                foreach ($products as $product) {
                    $product->stock += -$quantities[$product->id];
                    $product->save();
                }
            }
            $item->delete($id);
            DB::commit();

            $message = 'Penyesuaian stok ' . e($item->id) . ' telah dihapus.';
            // UserActivity::log(
            //     UserActivity::USER_GROUP_MANAGEMENT,
            //     'Hapus Grup Pengguna',
            //     $message,
            //     $item->toArray()
            // );
        }

        return redirect('admin/stock-adjustment')->with('info', $message);
    }
}
