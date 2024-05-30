<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\ProductCategory;
use App\Models\StockUpdate;
use App\Models\StockUpdateDetail;
use App\Models\UserActivity;
use Illuminate\Http\Request;
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

    public function detail(Request $request, $id)
    {
        $item = StockUpdate::with(['creation_user', 'closing_user'])->find($id);
        $details = StockUpdateDetail::with(['product'])->where('update_id', '=', $item->id)->get();
        return view('admin.stock-update.detail', compact('item', 'details'));
    }
}