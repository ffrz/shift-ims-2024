<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
