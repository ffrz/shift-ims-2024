<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function __construct()
    {
        ensure_user_can_access(AclResource::VIEW_REPORTS);
    }

    public function index()
    {
    }

    public function inventoryStock()
    {
        $items = Product::where('type', '=', Product::STOCKED)
            ->orderBy('code', 'asc')
            ->get();

        return view('admin.report.inventory-stock', compact('items'));
    }
}
