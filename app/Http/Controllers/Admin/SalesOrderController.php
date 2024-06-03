<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\Customer;
use App\Models\Party;
use App\Models\Product;
use App\Models\StockUpdate;
use App\Models\StockUpdateDetail;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalesOrderController extends Controller
{

    public function index()
    {
        $items = StockUpdate::with('party')->whereRaw('type = ' . StockUpdate::TYPE_SALES_ORDER)
            ->orderBy('id', 'desc')
            ->paginate(10);
        $filter = [];
        return view('admin.sales-order.index', compact('items', 'filter'));
    }

    public function create()
    {
        $item = new StockUpdate();
        $item->datetime = current_datetime();
        $item->type = StockUpdate::TYPE_SALES_ORDER;
        $item->status = StockUpdate::STATUS_OPEN;
        $item->id2 = StockUpdate::getNextId2($item->type);
        $item->save();
        return redirect('admin/sales-order/edit/' . $item->id)->with('info', 'Order penjualan telah dibuat.');
    }

    public function edit(Request $request, $id = 0)
    {
        $item = StockUpdate::findOrFail($id);
        if (!$item) {
            return redirect('admin/sales-order')->with('warning', 'Order penjualan tidak ditemukan.');
        }

        if ($request->method() == 'POST') {
            $data = ['Old Data' => $item->toArray()];
            $item->fill($request->all());

            if (empty($item->party_id)) {
                $item->party_id = null;
            }

            $product_by_ids = [];
            if (!empty($request->product_id)) {
                $products = Product::whereIn('id', $request->product_id)->get();
                foreach ($products as $product) {
                    $product_by_ids[$product->id] = $product;
                }
            }

            DB::beginTransaction();
            if ($request->action == 'complete' || $request->action == 'cancel') {
                $item->status = $request->action == 'complete' ? StockUpdate::STATUS_COMPLETED : StockUpdate::STATUS_CANCELED;
                $item->closed_datetime = current_datetime();
                $item->closed_by_uid = current_user_id();
            } else {
                $item->updated_datetime = current_datetime();
                $item->updated_by_uid = current_user_id();
            }

            $item->total_cost = 0;
            $item->total_price = 0;
            
            DB::delete('delete from stock_update_details where update_id = ?', [$item->id]);
            if (!empty($request->product_id)) {
                foreach ($request->product_id as $row_id => $product_id) {
                    $product = $product_by_ids[$product_id];
                    $d = new StockUpdateDetail();
                    $d->id = $row_id;
                    $d->update_id = $item->id;
                    $d->product_id = $product_id;
                    $d->quantity = -numberFromInput($request->qty[$row_id]);
                    $d->cost = $product->cost;
                    $d->stock_before = $product->stock;
                    $d->price = numberFromInput($request->price[$row_id]);
                    $item->total_cost += ($d->cost * $d->quantity);
                    $item->total_price += ($d->price * $d->quantity);

                    // saat ini belum ada diskon dan pajak, cukup set total dari total harga
                    $item->total = $item->total_price;

                    $d->save();
                }
            }

            if ($item->status == StockUpdate::STATUS_COMPLETED) {
                $details = StockUpdateDetail::with('product')->whereRaw('update_id=' . $item->id)->get();
                foreach ($details as $detail) {
                    $product = $detail->product;
                    $product->stock -= $detail->quantity;
                    $product->save();
                }
            }

            $item->save();

            $data['New Data'] = $item->toArray();

            DB::commit();

            if ($item->status == StockUpdate::STATUS_OPEN) {
                return redirect('admin/sales-order/edit/' . $item->id)->with('info', 'Order penjualan telah disimpan.');
            }

            return redirect('admin/sales-order/')->with('info', 'Order penjualan telah selesai.');
        }

        $tmp_products = Product::select(['id', 'code', 'description', 'stock', 'uom', 'price', 'barcode'])
            ->orderBy('code', 'asc')->get();
        $products = [];
        $product_code_by_ids = [];
        $barcodes = [];
        foreach ($tmp_products as $product) {
            $p = $product->toArray();
            $p['pid'] = $product->idFormatted();
            $product_code_by_ids[$product->id] = $p['pid'];
            $products[$product->idFormatted()] = $p;
            if (!empty($product->barcode)) {
                $barcodes[$product->barcode] = $product->idFormatted();
            }
        }
        $customers = Party::where('type', '=', Party::TYPE_CUSTOMER)->orderBy('name', 'asc')->get();
        $details = $item->details;
        return view('admin.sales-order.edit', compact('item', 'customers', 'products', 'barcodes', 'details', 'product_code_by_ids'));
    }

    public function saveDetail(Request $request)
    {
        $order = StockUpdate::findOrFail($request->order_id);
        DB::beginTransaction();
        DB::delete('delete from stock_update_details where update_id = ?', [$order->id]);
        foreach ($request->items as $k => $item) {
            $d = new StockUpdateDetail();
            $d->id = $k + 1;
            $d->update_id = $order->id;
            $d->product_id = $item['id'];
            $d->quantity = $item['qty'];
            $d->price = $item['price'];
            $d->save();
        }
        DB::commit();
        return response()->json([
            'status' => 'success',
            'data' => [],
            'message' => 'Information saved successfully!'
        ], 200);
    }

    public function delete($id)
    {
        $item = StockUpdate::findOrFail($id);
        if ($item->status == StockUpdate::STATUS_COMPLETED) {
            // rollback;
        }
        if ($item->delete($id)) {
            $message = 'Telah dihapus ' . e($item->name) . ' telah dihapus.';
            UserActivity::log(
                UserActivity::PRODUCT_CATEGORY_MANAGEMENT,
                'Hapus Kategori',
                $message,
                $item->toArray()
            );
        }

        return redirect('admin/sales-order')->with('info', $message);
    }
}
