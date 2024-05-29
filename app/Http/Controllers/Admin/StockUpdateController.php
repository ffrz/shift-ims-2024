<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\ProductCategory;
use App\Models\StockUpdate;
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
        $items = StockUpdate::all();
        return view('admin.stock-update.index', compact('items'));
    }

    public function edit(Request $request, $id = 0)
    {
    }

    public function delete($id)
    {
    }
}
