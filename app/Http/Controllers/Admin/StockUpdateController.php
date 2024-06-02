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

class StockUpdateController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $items = StockUpdate::where('status', '=', StockUpdate::STATUS_COMPLETED)
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('admin.stock-update.index', compact('items'));
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

            $message = 'Rekaman ' . e($item->id2Formatted()) . ' telah dihapus.';
            // UserActivity::log(
            //     UserActivity::STOCK_ADJUSTMENT_MANAGEMENT,
            //     'Hapus Stok Opname',
            //     $message,
            //     $item->toArray()
            // );
        }

        return redirect('admin/stock-update')->with('info', $message);
    }
    
    public function detail(Request $request, $id)
    {
        $item = StockUpdate::with(['created_by', 'closed_by'])->find($id);
        $details = StockUpdateDetail::with(['product'])->where('update_id', '=', $item->id)->get();
        if ($request->get('print') == 1) {
            return view('admin.stock-update.print', compact('item', 'details'));
        }
        return view('admin.stock-update.detail', compact('item', 'details'));
    }
}
