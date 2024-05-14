<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceOrder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $data = [
            'service_order_count' => ServiceOrder::where('order_status', '=', 'active')->count()
        ];
        return view('admin.dashboard.index', compact('data'));
    }
}
